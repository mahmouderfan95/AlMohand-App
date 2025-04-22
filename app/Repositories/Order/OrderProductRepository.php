<?php

namespace App\Repositories\Order;

use App\Models\Order\OrderProduct;
use App\Repositories\Admin\ValueAddedTaxRepository;
use App\Repositories\DirectPurchase\DirectPurchaseRepository;
use App\Repositories\Integration\IntegrationRepository;
use App\Repositories\Invoice\InvoiceRepository;
use App\Repositories\Product\ProductRepository;
use App\Repositories\Product\ProductSerialRepository;
use App\Repositories\Vendor\VendorProductRepository;
use Illuminate\Container\Container as Application;
use Illuminate\Support\Facades\DB;
use Prettus\Repository\Eloquent\BaseRepository;

class OrderProductRepository extends BaseRepository
{

    public function __construct(
        Application $app,
    )
    {
        parent::__construct($app);
    }

    public function store($orderProductData)
    {
        return $this->model->create($orderProductData);
    }

    public function show($id)
    {
        return $this->model
            ->where('id', $id)
            ->with(['order:id,customer_id'])
            ->first();
    }

    public function bestSellers($requestData)
    {
        $period = $requestData->input('best_sellers_period', 'today');
        $endDate = now()->endOfDay();
        switch ($period) {
            case 'yesterday':
                $startDate = now()->subDay();
                break;
            case 'last_7_days':
                $startDate = now()->subDays(7);
                break;
            case 'last_30_days':
                $startDate = now()->subDays(30);
                break;
            case 'current_month':
                    $startDate = now()->startOfMonth();
                    $endDate = now()->endOfMonth();
                    break;
            case 'previous_month':
                $startDate = now()->subMonth()->startOfMonth();
                $endDate = now()->subMonth()->endOfMonth();
                break;
            default:
                $startDate = now()->startOfDay();
                break;
        }

        return $this->model
            ->join('orders', 'orders.id', '=', 'order_products.order_id')
            ->select('product_id', DB::raw('SUM(quantity) as total_quantity'), DB::raw('SUM(orders.total) as total_amount'))
            ->whereHas('order', function($query) use ($startDate, $endDate) {
                $query->whereBetween('created_at', [$startDate, $endDate]);
            })
            ->groupBy('product_id')
            ->orderBy('total_quantity', 'DESC')
            ->with([
                'product:id,brand_id,image,price,status,type',
                'product.brand:id,status',
            ])
            ->take(10)
            ->get();
    }



    public function model(): string
    {
        return OrderProduct::class;
    }
}
