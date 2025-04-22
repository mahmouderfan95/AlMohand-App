<?php
namespace App\Repositories\Seller;

use App\Enums\Order\OrderProductStatus;
use App\Repositories\Order\FailedOrderReasonRepository;
use App\Repositories\Order\OrderProductRepository;
use App\Repositories\Product\ProductRepository;
use App\Traits\ApiResponseAble;

class OrderRepository{
    use ApiResponseAble;
    public function __construct(
        public ProductRepository $productRepository,
        public OrderProductRepository $orderProductRepository,
        public FailedOrderReasonRepository $failedOrderReasonRepository
        ){}
    public function getAllOrders($request,$orderProductType = null)
    {
        $status = in_array($request['status'], OrderProductStatus::getList()) ? $request['status'] : '';

        $orders = $this->getModel()
            ::orderBy('created_at', 'desc')
            ->with([
                'user:id,name',
                'order_products.product:id,brand_id,price,quantity,wholesale_price',
                'order_products.brand',
                'order_products.vendor:id,name',
                'order_histories',
            ])
            ->whereHas('order_products', function ($query) use ($status, $orderProductType) {
                if ($orderProductType) {
                    $query->where('type', $orderProductType);
                }
                if ($status)
                    return $query->where('status', $status);
                else
                    return $query;
            })
            ->paginate(PAGINATION_COUNT_ADMIN);
            return $orders;
    }
    public function checkSellerBalance($data)
    {
        $seller = auth('sellerApi')->user();
        $subTotal = 0;
        $tax = 0.0;
        foreach($data['order_products'] as $orderProduct)
        {
            $product = $this->productRepository->showProductByIdAndCategoryId($orderProduct['product_id'], $orderProduct['category_id']);
            if(!$product){
                return $this->ApiErrorResponse([],'product not found');
            }
            // calculate subtotal and taxes
            $subTotal += $product->price * $orderProduct['quantity'];
        }
        $total = round($subTotal, 3) + $tax;
        $data['total'] = $total;
        $data['sub_total'] = $subTotal;
        $data['tax'] = $tax;
        if($seller->balance < $data['total']){
            return $this->ApiErrorResponse([],'balance not enough');
        }
        return $data;
    }
    public function createOrder($data)
    {
        return $this->getModel()::create($data);
    }

    private function getModel()
    {
        return \App\Models\Order\Order::class;
    }
}
