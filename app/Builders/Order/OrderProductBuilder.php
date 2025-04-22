<?php

namespace App\Builders\Order;

use App\Enums\GeneralStatusEnum;
use App\Enums\Integration\MintrouteIntegrationType;
use App\Enums\InvoiceType;
use App\Enums\Order\OrderPaymentMethod;
use App\Enums\Order\OrderProductStatus;
use App\Enums\Order\OrderProductType;
use App\Enums\VendorStatus;
use App\Models\DirectPurchase;
use App\Models\DirectPurchasePriority;
use App\Models\Distributor\DistributorPosTerminal;
use App\Models\Order\Order;
use App\Models\Order\OrderProduct;
use App\Models\Product\Product;
use App\Models\VendorProduct;
use App\Repositories\DirectPurchase\DirectPurchaseRepository;
use App\Repositories\Integration\IntegrationRepository;
use App\Repositories\Invoice\InvoiceRepository;
use App\Repositories\Order\FailedOrderReasonRepository;
use App\Repositories\Order\OrderProductRepository;
use App\Repositories\Order\OrderProductSerialRepository;
use App\Repositories\Product\ProductRepository;
use App\Repositories\Product\ProductSerialRepository;
use App\Repositories\Vendor\VendorProductRepository;
use App\Services\General\OnlineShoppingIntegration\IntegrationServiceFactory;

class OrderProductBuilder extends AbstractOrderProductBuilder
{
    private Order $order;
    private OrderProduct $orderProduct;
    private string $orderProductType;
    private int $realQuantity = 0;
    private $successVendorProduct = null;
    private $orderProductSerials = [];
    private $purchaseDone = false;
    private $failedReasons = [];

    public function __construct(
        /////////////////////////////////////////////////////////////////////////////
        private OrderProductRepository              $orderProductRepository,
        private IntegrationRepository               $integrationRepository,
        private OrderProductSerialRepository        $orderProductSerialRepository,
        private ProductSerialRepository             $productSerialRepository,
        private ProductRepository                   $productRepository,
        private VendorProductRepository             $vendorProductRepository,
        private InvoiceRepository                   $invoiceRepository,
        private DirectPurchaseRepository            $directPurchaseRepository,
        private FailedOrderReasonRepository         $failedOrderReasonRepository,
    )
    {}

    public function set(Order $order, Product $product, int $quantity): self
    {
        $data = [
            'order_id' => $order->id,
            'product_id' => $product->id,
            'brand_id' => $product->brand_id,
            'type' => OrderProductType::getTypeSerial(),
            'status' => OrderProductStatus::getTypeWaiting(),
            'total' => ($product->price * $quantity),
            'quantity' => $quantity,
            'cost_price' => $product->cost_price,
            'wholesale_price' => $product->wholesale_price,
            'price' => $product->price,
        ];
        $this->orderProduct = $this->orderProductRepository->store($data);
        $this->order = $order;
        $this->realQuantity = $quantity;
        $this->orderProductType = OrderProductType::getTypeSerial();
        $this->successVendorProduct = null;
        $this->orderProductSerials = [];
        $this->purchaseDone = false;
        $this->failedReasons = [];
        return $this;
    }

    public function reset(OrderProduct $orderProduct): self
    {
        $this->orderProduct = $orderProduct;
        $this->order = $orderProduct->order;
        $this->realQuantity = $orderProduct->quantity;
        $this->orderProductType = $orderProduct->type;
        $this->successVendorProduct = null;
        $this->orderProductSerials = [];
        $this->purchaseDone = false;
        $this->failedReasons = [];
        return $this;
    }

    public function getOrderProduct(): OrderProduct
    {
        return $this->orderProduct;
    }

    public function selectOrderWithType(): self
    {
        // that mean it is serial order
        $this->orderWithSerials();
        return $this;
    }

    public function updateOrderProductPrices(): self
    {
        $this->orderProduct->update([
           'quantity' => $this->realQuantity,
           'profit' => ($this->orderProduct->price - $this->orderProduct->wholesale_price),
           'total' => $this->getTotalPrice(),
        ]);

        return $this;
    }

    public function storeFailedOrderReasons(): self
    {
        if (count($this->failedReasons) == 0){
            return $this;
        }
        // change orderProduct to rejected
        $this->orderProduct->status = OrderProductStatus::getTypeRejected();
        $this->orderProduct->save();
        // store reason of fail
        $failedOrderReason = [
            'order_id' => $this->orderProduct->order_id,
            'order_product_id' => $this->orderProduct->id,
            'reason' => json_encode($this->failedReasons),
        ];
        $this->failedOrderReasonRepository->store($failedOrderReason);
        return $this;
    }

    ////////////////////////////////////////////////////////////////////
    /////////////////////// Assets /////////////////////////////////////
    ////////////////////////////////////////////////////////////////////

    private function orderWithSerials(): self
    {
        $updateQuantity = false;
        // get product from direct purchase priorities
        $directPurchase = $this->directPurchaseRepository->showByProductId($this->orderProduct->product->id);
        // that mean we make this based on priority of live integrations
        if ($directPurchase && $directPurchase->status == GeneralStatusEnum::getStatusActive() && $directPurchase->directPurchasePriorities) {
            // make purchase based on priority
            $this->purchaseBasedOnPriority($directPurchase);
        }
        // check if purchaseDone and liveIntegrationError fail
        if (!$this->purchaseDone) {
            // that mean it is serials from table because of all priorities false
            // check quantity for product
            if ($this->orderProduct->product->quantity < $this->orderProduct->quantity){
                $this->failedReasons[] = 'Quantity not enough.';
                return $this;
            }
            // that is for update quantity after get serials from stock
            $this->realQuantity = $this->orderProduct->quantity;
            $updateQuantity = true;
        }

        if (empty($this->orderProductSerials)){
            // check if there are available serials
            $this->orderProductSerials = $this->productSerialRepository->GetFirstExpireFreeSerialsFromProcedure($this->orderProduct);
            if (!$this->orderProductSerials) {
                $this->failedReasons[] = 'Quantity not enough.';
                return $this;
            }
        }else{
            $this->realQuantity = count($this->orderProductSerials);
        }

        // store order product's serials
        $orderProductSerials = $this->orderProductSerialRepository->store($this->orderProductSerials, $this->orderProduct);
        // change serials to sold
        $serialIds = $this->orderProductSerials->pluck('id')->toArray();
        $this->productSerialRepository->changeSerialsToSold($serialIds);
        // update quantity value
        if ($updateQuantity)
            $this->productRepository->updateProductQuantity($this->orderProduct->product->id, $this->orderProduct->quantity);
        // complete order status
        $this->orderProduct->status = OrderProductStatus::getTypeCompleted();
        $this->orderProduct->vendor_id = $this->successVendorProduct?->vendor_id;
        $this->orderProduct->save();
        // fetch serials that was sold
        $this->orderProductSerials = $orderProductSerials;
        return $this;
    }

    private function makeSerialsLiveIntegration(VendorProduct $vendorProduct): array
    {
        $data = ['success' => false , 'error' => null];
        // call method from service
        $vendorIntegrate = $this->integrationRepository->showById($vendorProduct->vendor->integration_id);
        $vendorIntegrate->name = MintrouteIntegrationType::resolve($vendorIntegrate->name, OrderProductType::getTypeSerial());
        $service = IntegrationServiceFactory::create($vendorIntegrate);
        if (! $service){
            $data['error'] = 'Not found integration service';
            return $data;
        }
        // store invoice for these serials
        $invoice_number = time();
        $invoiceData = [
            'vendor_id' => $vendorProduct->vendor->id,
            'product_id' => $vendorProduct->product->id,
            'user_id' => null,
            'invoice_number' => $invoice_number,
            'type' => InvoiceType::getTypeAuto(),
            'quantity' => 0
        ];
        $invoice = $this->invoiceRepository->storeInvoice($invoiceData);
        if (! $invoice){
            $data['error'] = 'Failed when creating invoice';
            return $data;
        }
        // make order from integration
        $requestData = [
            'product_id' => $vendorProduct->vendor_product_id,
            'patch_number' => $invoice_number,
            'quantity' => $this->orderProduct->quantity,
            'original_product_id' => $vendorProduct->product_id,
            'invoice_id' => $invoice->id,
        ];
        if (! method_exists($service, 'purchaseProduct')) {
            $data['error'] = 'No vendor available.';
            return $data;
        }
        $order = $service->purchaseProduct($requestData);
        if (! $order || count($order['products']) == 0){
            $this->invoiceRepository->deleteInvoice($invoice->id);
            $data['error'] = 'No free serials in vendor.';
            return $data;
        }
        // store serials
        $this->orderProductSerials = $this->productSerialRepository->store($order['products']);
        // update invoice quantity
        $invoice->quantity = $order['quantity'];
        $invoice->price = $order['price'];
        $invoice->save();

        $data['success'] = true;
        return $data;
    }

    private function purchaseBasedOnPriority(DirectPurchase $directPurchase): void
    {
        foreach ($directPurchase->directPurchasePriorities as $directPurchasePriority){
            // get first vendor available for this product with id in this vendor integration
            $vendorProduct = $this->vendorProductRepository
                ->showByVendorIdAndProductId($directPurchase->product_id, $directPurchasePriority->vendor_id);
            if (! $vendorProduct || $vendorProduct->vendor->integration_id == null || $vendorProduct->vendor->status != VendorStatus::getTypeApproved()){
                continue;
            }
            // make live integration with set serials and invoice
            $liveIntegrationResult = $this->makeLiveIntegration($vendorProduct, $directPurchase, $directPurchasePriority);
            if (! $liveIntegrationResult['success']){
                $this->failedReasons[] = $liveIntegrationResult['error'];
                continue;
            }else{
                // fetch success vendor product and make purchase true
                $this->successVendorProduct = $vendorProduct;
                $this->purchaseDone = true;
                break;
            }
        }

    }

    private function makeLiveIntegration(VendorProduct $vendorProduct,DirectPurchase $directPurchase,DirectPurchasePriority $directPurchasePriority): array
    {
        return $this->makeSerialsLiveIntegration($vendorProduct);
    }

    private function getTotalPrice(): float
    {
        if ($this->order == OrderPaymentMethod::getMada()){
            // decrease from price based on mada
            return $this->realQuantity * $this->orderProduct->price;
        }
        else{
            // decrease from wholesale_price based on balance
            return $this->realQuantity * $this->orderProduct->wholesale_price;

        }
    }

}
