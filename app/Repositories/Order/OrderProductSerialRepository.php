<?php

namespace App\Repositories\Order;

use App\Enums\Order\OrderPaymentMethod;
use App\Enums\Order\OrderProductStatus;
use App\Enums\Order\OrderStatus;
use App\Http\Resources\Pos\Order\AllOrdersResource;
use App\Http\Resources\Pos\Order\GroupedOrderResource;
use App\Models\Distributor\DistributorPosTerminal;
use App\Models\Order\OrderProductSerial;
use App\Repositories\Language\LanguageRepository;
use Carbon\Carbon;
use Illuminate\Container\Container as Application;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Request;
use Prettus\Repository\Eloquent\BaseRepository;

class OrderProductSerialRepository extends BaseRepository
{

    public function __construct(Application $app, private LanguageRepository $languageRepository)
    {
        parent::__construct($app);
    }

    public function index(array $data, $distributor_pos_terminal_id = null)
    {
        $dateFrom = !empty($data['date_from']) ? Carbon::parse($data['date_from'])->startOfDay() : null;
        $dateTo = !empty($data['date_to']) ? Carbon::parse($data['date_to'])->endOfDay() : null;
        $search = !empty($data['search']) ?? null;
        $print_status = $data['print_status'] ?? null;
        $merchant_id = $data['merchant_id'] ?? null;
        $payment_method = $data['payment_method'] ?? null;
        $langId = $this->languageRepository->getLangByCode(app()->getLocale())->id;
        $orderProductSerialsQuery = $this->model->query()
            ->with('orderProduct.order.owner')
            ->join('order_products', 'order_products.id', '=', 'order_product_serials.order_product_id')
            ->join('orders', 'orders.id', '=', 'order_products.order_id')
            ->join('products', 'products.id', '=', 'order_products.product_id')
            ->leftJoin('product_translations', function (JoinClause $join) use ($langId) {
                $join->on("product_translations.product_id", '=', "products.id")
                    ->where("product_translations.language_id", $langId);
            })
            ->join('brands', 'brands.id', '=', 'products.brand_id')
            ->leftJoin('brand_translations', function (JoinClause $join) use ($langId) {
                $join->on("brand_translations.brand_id", '=', "brands.id")
                    ->where("brand_translations.language_id", $langId);
            })
            //->join('distributor', 'orders.owner_id', '=', 'distributor.id')
            ->select(
                'order_product_serials.*',
                'order_products.created_at as created_at',
                'order_products.status',
                'products.id as product_id',
                'product_translations.name',
            )
            ->where('orders.status', OrderStatus::COMPLETED)
            ->where('order_products.status', OrderProductStatus::getTypeCompleted());

        if (!empty($search)) {
            $orderProductSerialsQuery->where('product_translations.name', 'LIKE', "%{$search}%");
        }

        if ($dateFrom && $dateTo) {
            $orderProductSerialsQuery->whereBetween('order_products.created_at', [$dateFrom, $dateTo]);
        }

        if (!empty($merchant_id)) {
            $distributor_pos_terminals = DistributorPosTerminal::query()->select('id', 'distributor_id')
                ->where('distributor_id', '=', $merchant_id)
                ->pluck('id')->toArray();
            $orderProductSerialsQuery->whereIn('orders.owner_id', $distributor_pos_terminals);
        }

        if (!empty($distributor_pos_terminal_id)) {
            $orderProductSerialsQuery->where('orders.owner_id', $distributor_pos_terminal_id);
        }

        if (!empty($print_status)) {
            if ($print_status == 1) {
                $orderProductSerialsQuery->where('order_product_serials.print_count', '>', 0);
            } else {
                $orderProductSerialsQuery->where('order_product_serials.print_count', '=', 0);
            }
        }

        if (!empty($payment_method)) {
            $orderProductSerialsQuery->where('orders.payment_method', '=', $payment_method);
        }

        return $orderProductSerialsQuery->orderByDesc('orders.created_at');
    }

    public function getPosOrdersReport(array $data, $distributor_pos_terminal_id = null): array
    {
        $orderProductSerials = $this->index($data, $distributor_pos_terminal_id)->paginate(PAGINATION_COUNT_APP);

        $serialsFilteredByDate = $orderProductSerials->getCollection()->groupBy(function ($order) {
            return Carbon::parse($order->created_at)->toDateString();
        });

        return [
            'current_page' => $orderProductSerials->currentPage(),
            'per_page' => $orderProductSerials->perPage(),
            'total' => $orderProductSerials->total(),
            'data' => new GroupedOrderResource($serialsFilteredByDate),
        ];
    }

    public function getDailySalesReport(array $data)
    {
        return $this->index($data)->paginate(PAGINATION_COUNT_APP);
    }

    public function getPosOrders(array $data, $distributor_pos_terminal_id)
    {
        return $this->index($data, $distributor_pos_terminal_id)->paginate(PAGINATION_COUNT_APP);
    }

    public function getTotalProfitReport(array $data)
    {
        $orders = $this->index($data)->paginate(PAGINATION_COUNT_APP);

        $orders->getCollection()->transform(function ($order) {
            $order_product = $order->orderProduct;
            $order->total_cost = $order_product?->cost_price * $order_product?->quantity;
            $order->total_price = $order_product?->price * $order_product?->quantity;
            $order->total_profit = ($order_product?->wholesale_price - $order_product?->cost_price) * $order_product?->quantity;
            $order->total = $order->orderProduct->total;
            return $order;
        });

        return [
            'orders' => $orders,
            'total_profit' => $orders->getCollection()->sum('total_profit'),
            'total_sales' => $orders->getCollection()->sum('total'),
            'total_cost' => $orders->getCollection()->sum('total_cost'),
        ];
    }

    public function getTotalOrdersAmountWihPaymentMethod(array $data, string $payment_method)
    {
        $data['payment_method'] = $payment_method;
        return $this->index($data)->sum('orders.total');
    }

    public function getTotalOrderedCardsQuantity($data)
    {
        return $this->index($data)->sum('order_products.quantity');
    }

    public function showByOrderId($orderId)
    {
        $langId = $this->languageRepository->getLangByCode(app()->getLocale())->id;

        return $this->model
            ->join('order_products', 'order_products.id', '=', 'order_product_serials.order_product_id')
            ->join('products', 'products.id', '=', 'order_products.product_id')
            ->leftJoin('product_translations', function (JoinClause $join) use ($langId) {
                $join->on("product_translations.product_id", '=', "products.id")
                    ->where("product_translations.language_id", $langId);
            })
            ->select(
                'order_product_serials.*',
                'products.id as product_id',
                'product_translations.name as product_name',
                'products.price as product_price',
                DB::raw("CONCAT('" . asset('storage/uploads/products') . "', '/', products.image) AS full_image_url"),
            )
            ->where('order_product_serials.order_id', $orderId)
            ->get();
    }

    public function showForAuthByIds(array $ids, Authenticatable $authenticatable)
    {
        return $this->model
            ->whereHas('order.owner', function ($query) use ($authenticatable) {
                $query->where('distributor_pos_terminals.id', $authenticatable->id);
            })
            ->whereIn('id', $ids)
            ->get();
    }

    public function store($serials, $orderProduct)
    {
        $printCount = 3;
        $orderProductSerials = [];
        foreach ($serials as $serial){
            $orderProductSerials[] = $this->model->create([
                'order_id' => $orderProduct->order_id,
                'order_product_id' => $orderProduct->id,
                'product_serial_id' => $serial->id,
                'serial' => $serial->serial,
                'scratching' => $serial->scratching,
                'buying' => $serial->buying,
                'expiring' => $serial->expiring,
                'print_count' => $printCount,
                'max_print_count' => $printCount,
            ]);
        }
        return $orderProductSerials;
    }


    public function model(): string
    {
        return OrderProductSerial::class;
    }
}
