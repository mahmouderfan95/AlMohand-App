<?php

namespace App\Repositories\Order;

use App\DTO\Admin\Report\SalesReportDto;
use App\Enums\Order\OrderProductStatus;
use App\Enums\Order\OrderProductType;
use App\Enums\Order\OrderStatus;
use App\Helpers\FileUpload;
use App\Models\Order\Order;
use App\Models\Order\OrderHistory;
use App\Repositories\Admin\ValueAddedTaxRepository;
use App\Repositories\Currency\CurrencyRepository;
use App\Repositories\Product\ProductRepository;
use App\Traits\ApiResponseAble;
use Illuminate\Container\Container as Application;
use Prettus\Repository\Eloquent\BaseRepository;

class OrderRepository extends BaseRepository
{
    use FileUpload, ApiResponseAble;


    public function __construct(
        Application                          $app,
        private OrderProductRepository       $orderProductRepository,
        private ProductRepository            $productRepository,
        private ValueAddedTaxRepository      $valueAddedTaxRepository,
        private CurrencyRepository           $currencyRepository,
    )
    {
        parent::__construct($app);
    }


    public function getAllOrders($requestData, $orderProductType = null)
    {
        $status = in_array($requestData->input('status'), OrderProductStatus::getList()) ? $requestData->input('status') : '';
        $keyword = $requestData->input('keyword');
        $orders = $this->model->query()
            ->orderBy('created_at', 'desc')
            ->with([
                'user:id,name',
                'owner',
                'order_products.product:id,brand_id',
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
            })->when(!empty($keyword), function ($query) use ($keyword) {
                $query->where(function ($q) use ($keyword) {
                    $q->where('orders.id', '=', $keyword)
                        ->orWhereHas('owner.posTerminal', function ($q) use ($keyword) {
                            $q->where('name', 'LIKE', "%{$keyword}%");
                        })
                        ->orWhereHas('owner.distributor.translations', function ($q) use ($keyword) {
                            $q->where('name', 'LIKE', "%{$keyword}%");
                        });
                });
            })
            ->distinct()
            ->paginate(PAGINATION_COUNT_ADMIN);

        return $orders;
    }

    public function getDailySalesReport(SalesReportDto $dto)
    {
        $orders = $this->model->query()->orderByDesc('created_at')
            ->where('status', '=', OrderStatus::COMPLETED)
            ->with(['orderProduct', 'owner', 'order_products.brand']);
    }

    public function waitingOrdersCount()
    {
        return $this->model
        ->whereHas('order_products', function ($query) {
            return $query->where('status', OrderProductStatus::getTypeWaiting());
        })->count();
    }

    public function store($requestData)
    {
        // store new order
        return $this->model->create($requestData);

    }

    public function show($id)
    {
        $order = $this->model->query()
            ->with([
                'currency:id',
                'owner',
                // 'customer.customerGroup:id',
                'user:id,name',
                'userPulled:users.id,users.name',
                'order_products.brand',
                'order_products.orderProductSerials',
                'order_products.product',
                // 'order_products.options.optionDetails:options.id,key,type',
                // 'order_products.options.optionValues.optionValueDetails:id,key',
            ])
            ->where('id', $id)
            ->first();
        if (! $order)
            return false;
        // return $order;
        return $this->formatOrderProductsWithoutHashedTopUp($order);
    }

    public function getPendingOrderById($id)
    {
        return $this->model->where('id', $id)->where('status', OrderStatus::PENDING)->first();
    }

    public function destroy($id)
    {
        return $this->model->where('id', $id)->delete();
    }

    public function update_status($data_request, $order_id)
    {
        $order = $this->model->find($order_id);
        $order->update($data_request);
        return $order->load('owner', 'order_products.product', 'order_products.brand', 'order_products.vendor',
            'order_products.product.productSerials', 'order_histories');

    }

    public function save_notes($data_request)
    {
        $order = $this->model->find($data_request['order_id']);
        $order->status = $data_request['status'];
        $order->save();
        OrderHistory::create([
            'order_id' => $order->id,
            'status' => $data_request['status'],
            'note' => $data_request['note'] ?? null,
        ]);
        return $order->load('owner', 'order_products.product', 'order_products.brand', 'order_products.vendor',
            'order_products.product.productSerials', 'order_histories');

    }

    public function get_status($data_request, $status)
    {
        return $this->model->with(['owner', 'order_products.product', 'order_products.brand', 'order_products.vendor',
            'order_products.product.productSerials', 'order_histories'])->where('status',$status)->get();

    }

    public function get_customer_orders($customer_id)
    {
        return $this->model->with(['owner', 'order_products.product', 'order_products.brand', 'order_products.vendor',
            'order_products.product.productSerials', 'order_histories'])->where('customer_id',$customer_id)->get();

    }

    public function ordersCount()
    {
        return $this->model->count();
    }

    public function ordersTotalLastDay()
    {
        return $this->model
            ->where('created_at', '>=', now()->subDay())
            ->sum('total');
    }

    public function destroy_selected($ids)
    {

        foreach ($ids as $id) {
            $order = $this->model->findOrFail($id);
            if ($order)
                $order->delete();
        }
        return true;
    }

    public function trash()
    {
        return $this->model->onlyTrashed()->get();
    }

    public function restore($id)
    {
        return $this->model->withTrashed()->find($id)->restore();

    }

    /////////////////////////////////////////////////////////////////////////////////
    ////////////////////////////////// Assets////////////////////////////////////////
    /////////////////////////////////////////////////////////////////////////////////
    public function formatOrderProductsWithHashedAll($order)
    {
        $order->order_products->map(function ($orderProduct) {
            $orderProduct->is_manual = 0;
            // if ($orderProduct->type == OrderProductType::getTypeTopUp() && $orderProduct->orderProductSerials){
            if ($orderProduct->type == $orderProduct->orderProductSerials){
                $orderProduct->is_manual = 1;
                $orderProduct->orderProductSerials->map(function ($orderProductSerial) {
                    $orderProductSerial->serial = $this->maskExceptLastN($orderProductSerial->serial, 3);
                    $orderProductSerial->scratching = $this->maskExceptLastN($orderProductSerial->scratching, 3);
                    return $orderProductSerial;
                });
            }
            elseif ((in_array($orderProduct->type, [OrderProductType::getTypeSerial(), OrderProductType::getTypeGift()])) && $orderProduct->orderProductSerials){
                $orderProduct->orderProductSerials->map(function ($orderProductSerial) {
                    $orderProductSerial->serial = $this->maskExceptLastN($orderProductSerial->serial, 3);
                    $orderProductSerial->scratching = $this->maskExceptLastN($orderProductSerial->scratching, 3);
                    return $orderProductSerial;
                });
            }
            return $orderProduct;

        });

        return $order;
    }

    public function formatOrderProductsWithoutHashedTopUp($order)
    {
        $order->order_products->map(function ($orderProduct) {
            $orderProduct->is_manual = 0;
            // if ($orderProduct->type == OrderProductType::getTypeTopUp() && in_array($orderProduct->status, [OrderProductStatus::getTypeWaiting(), OrderProductStatus::getTypeInProgress()])
            if ($orderProduct->type == in_array($orderProduct->status, [OrderProductStatus::getTypeWaiting(), OrderProductStatus::getTypeInProgress()])
            ){
                $orderProduct->is_manual = 1;
            }
            elseif ($orderProduct->type == OrderProductType::getTypeSerial() && $orderProduct->orderProductSerials){
                $orderProduct->orderProductSerials->map(function ($orderProductSerial) {
                    $orderProductSerial->serial = $this->maskExceptLastN($orderProductSerial->serial, 3);
                    $orderProductSerial->scratching = $this->maskExceptLastN($orderProductSerial->scratching, 3);
                    return $orderProductSerial;
                });
            }
            return $orderProduct;

        });

        return $order;
    }

//  public function formatOrderProducts($order)
//    {
//        return $order->order_products->flatMap(function ($orderProduct) {
//            $item = [
//                "id" => $orderProduct->id,
//                "product_id" => $orderProduct->product_id,
//                "type" => $orderProduct->type,
//                "unit_price" => $orderProduct->unit_price,
//                "status" => $orderProduct->status,
//                "product_name" => $orderProduct->product->name,
//                "product_image" => $orderProduct->product->image,
//                "quantity" => $orderProduct->quantity,
//                "is_manual" => 0,
//            ];
//            if (count($orderProduct->orderProductSerials) > 0){
//                return $orderProduct->orderProductSerials->map(function ($orderProductSerial) use ($orderProduct, $item) {
//                    if ($item['type'] == OrderProductType::getTypeSerial()) {
//                        $item = array_merge($item, [
//                            "product_serial_id" => $orderProductSerial->product_serial_id,
//                            "serial" => $this->maskExceptLastN($orderProductSerial->serial, 3),
//                            "scratching" => $this->maskExceptLastN($orderProductSerial->scratching, 3),
//                            "buying" => $orderProductSerial->buying,
//                            "expiring" => $orderProductSerial->expiring,
//                        ]);
//                        $item['options'] = [];
//                    } else {
//                        $item = array_merge($item, [
//                            "is_manual" => 1,
//                            "product_serial_id" => $orderProductSerial->product_serial_id,
//                            "serial" => $orderProductSerial->serial,
//                            "scratching" => $orderProductSerial->scratching,
//                            "buying" => $orderProductSerial->buying,
//                            "expiring" => $orderProductSerial->expiring,
//                        ]);
//                        $item['options'] = $orderProduct->options;
//                    }
//                    return $item;
//                });
//            }else{
//                return [
//                    array_merge($item, [
//                        "product_serial_id" => null,
//                        "serial" => null,
//                        "scratching" => null,
//                        "buying" => null,
//                        "expiring" => null,
//                        "options" => $orderProduct->options
//                    ])
//                ];
//            }
//        });
//    }

    private function maskExceptLastN($string, $keepLast)
    {
        $length = strlen($string);
        if ($keepLast >= $length) {
            return str_repeat('*', $length);
        }
        return str_repeat('*', $length - $keepLast) . substr($string, -$keepLast);
    }

    /**
     * Order Model
     *
     * @return string
     */
    public function model(): string
    {
        return Order::class;
    }
}
