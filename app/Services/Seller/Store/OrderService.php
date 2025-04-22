<?php
namespace App\Services\Seller\Store;

use App\Http\Resources\Seller\OrderResource;
use App\Repositories\Admin\ValueAddedTaxRepository;
use App\Repositories\DirectPurchase\DirectPurchaseRepository;
use App\Repositories\Integration\IntegrationRepository;
use App\Repositories\Invoice\InvoiceRepository;
use App\Repositories\Order\FailedOrderReasonRepository;
use App\Repositories\Order\OrderHistoryRepository;
use App\Repositories\Order\OrderProductRepository;
use App\Repositories\Order\OrderProductSerialRepository;
use App\Repositories\Product\ProductRepository;
use App\Repositories\Product\ProductSerialRepository;
use App\Repositories\Seller\OrderRepository;
use App\Repositories\Vendor\VendorProductRepository;
use App\Traits\ApiResponseAble;
use Illuminate\Http\Request;

class OrderService{
    use ApiResponseAble;
    public function __construct(
        public OrderRepository $orderRepository,
        public ProductRepository $productRepository,
        public OrderProductRepository $orderProductRepository,
        public FailedOrderReasonRepository $failedOrderReasonRepository,
        public DirectPurchaseRepository $directPurchaseRepository,
        public VendorProductRepository $vendorProductRepository,
        public ProductSerialRepository         $productSerialRepository,
        public OrderProductSerialRepository    $orderProductSerialRepository,
        public ValueAddedTaxRepository $valueAddedTaxRepository,
        public InvoiceRepository $invoiceRepository,
        public IntegrationRepository $integrationRepository,
        public OrderHistoryRepository $orderHistoryRepository){}
    public function index(Request $request)
    {
        $orders = $this->orderRepository->getAllOrders($request);
        if($orders->count() > 0)
            return $this->ApiSuccessResponse(OrderResource::collection($orders));
        return $this->ApiErrorResponse([],'data not found');
    }

}
