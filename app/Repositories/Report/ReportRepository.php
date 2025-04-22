<?php

namespace App\Repositories\Report;

use App\Enums\BalanceRequest\BalanceRequestStatusEnum;
use App\Enums\GeneralStatusEnum;
use App\Helpers\SettingsHelper;
use App\Models\BalanceLog\BalanceLog;
use App\Models\BalanceRequest\BalanceRequest;
use App\Models\Distributor\DistributorPosTerminal;
use App\Models\Order\Order;
use App\Models\POSTerminal\PosTerminalTransaction;
use App\Models\SalesRep\SalesRep;
use App\Repositories\Distributor\DistributorPosTerminalRepository;
use App\Repositories\Language\LanguageRepository;
use Carbon\Carbon;
use Illuminate\Container\Container as Application;
use Illuminate\Database\Query\JoinClause;
use Prettus\Repository\Eloquent\BaseRepository;

class ReportRepository extends BaseRepository
{


    public function __construct(Application $app)
    {
        parent::__construct($app);
    }


    public function orderReports($distributor_pos_terminal, $filter, $from = null, $to = null, $payment_method = null)
    {
        $query = Order::where('owner_id', $distributor_pos_terminal)
            ->select(['id', 'created_at', 'total', 'sub_total', 'status', 'payment_method', 'real_amount', 'vat', 'tax'])
            ->withCount('order_products')
            ->with(['order_products' => function ($query) {
                $query->select(['id', 'order_id', 'product_id', 'quantity', 'price', 'total']);
            }]);

        // Apply date filters
        switch ($filter) {
            case 'today':
                $query->whereDate('created_at', Carbon::today());
                break;

            case 'tomorrow':
                $query->whereDate('created_at', Carbon::tomorrow());
                break;

            case 'specific_date_range':
                if ($from && $to) {
                    $query->whereBetween('created_at', [Carbon::parse($from), Carbon::parse($to)]);
                }
                break;

            default:
                // Default to today's filter
                $query->whereDate('created_at', Carbon::today());
                break;
        }

        // Apply payment method filter if provided
        if ($payment_method) {
            $query->where('payment_method', $payment_method);
        }

        return $query->paginate(PAGINATION_COUNT_ADMIN);
    }


    public function balanceReports($distributor_pos_terminal, $filter, $from = null, $to = null)
    {
        $query = PosTerminalTransaction::whereHas('order', function ($q) use ($distributor_pos_terminal) {
            $q->where('owner_id', $distributor_pos_terminal);
        })->select([
            'id',
            'transaction_id',
            'order_id',
            'amount',
            'currency_code',
            'type',
            'status',
            'payment_method',
            'transaction_date',
            'balance_before',
            'balance_after',
        ])->with([
            'order:id,total,status',
            'order.order_products:id,order_id,product_id,quantity,price,total',
            'order.order_products.product.translations'

        ]);

        // Apply date filters
        switch ($filter) {
            case 'today':
                $query->whereDate('transaction_date', Carbon::today());
                break;

            case 'tomorrow':
                $query->whereDate('transaction_date', Carbon::tomorrow());
                break;

            case 'specific_date_range':
                if ($from && $to) {
                    $query->whereBetween('transaction_date', [Carbon::parse($from), Carbon::parse($to)]);
                }
                break;

            default:
                // Default to today's filter'
                $query->whereDate('transaction_date', Carbon::today());
                break;
        }

        return $query->paginate(PAGINATION_COUNT_ADMIN);
    }

    public function commissionReports($pos_terminal_id, $filter, $from = null, $to = null)
    {

        $query = PosTerminalTransaction::with(['balanceLog', 'order.order_products.product.translations'])->where('pos_terminal_id', $pos_terminal_id)
            ->whereHas('balanceLog', function ($q) {
                $q->where('balance_type', 'commission');
            });

        // Apply date filters
        switch ($filter) {
            case 'today':
                $query->whereDate('transaction_date', Carbon::today());
                break;

            case 'tomorrow':
                $query->whereDate('transaction_date', Carbon::tomorrow());
                break;

            case 'specific_date_range':
                if ($from && $to) {
                    $query->whereBetween('transaction_date', [Carbon::parse($from), Carbon::parse($to)]);
                }
                break;

            default:
                // Default to today's filter
                $query->whereDate('transaction_date', Carbon::today());
                break;
        }
        return $query->paginate(PAGINATION_COUNT_ADMIN);
    }


    public function pointReports($pos_terminal_id, $filter, $from = null, $to = null)
    {

        $query = PosTerminalTransaction::with(['balanceLog', 'order.order_products.product.translations'])->where('pos_terminal_id', $pos_terminal_id)
            ->whereHas('balanceLog', function ($q) {
                $q->where('balance_type', 'points');
            });

        // Apply date filters
        switch ($filter) {
            case 'today':
                $query->whereDate('transaction_date', Carbon::today());
                break;

            case 'tomorrow':
                $query->whereDate('transaction_date', Carbon::tomorrow());
                break;

            case 'specific_date_range':
                if ($from && $to) {
                    $query->whereBetween('transaction_date', [Carbon::parse($from), Carbon::parse($to)]);
                }
                break;

            default:
                // Default to  filter
                break;
        }
        return $query->paginate(PAGINATION_COUNT_ADMIN);
    }

    public function mainCommissionReports($pos_terminal_id, $filter, $from = null, $to = null)
    {
        $query = PosTerminalTransaction::orderBy('created_at', 'desc')->with([
            'balanceLog',
            'order.order_products.product.translations'
        ])
            ->where('pos_terminal_id', $pos_terminal_id)
            ->whereHas('order')
            ->whereHas('balanceLog', function ($q) {
                $q->where('balance_type', 'commission');
            });

        switch ($filter) {
            case 'today':
                $query->whereDate('transaction_date', Carbon::today());
                break;
            case 'tomorrow':
                $query->whereDate('transaction_date', Carbon::tomorrow());
                break;
            case 'specific_date_range':
                if ($from && $to) {
                    $query->whereBetween('transaction_date', [Carbon::parse($from), Carbon::parse($to)]);
                }
                break;
            default:
                break;
        }

        $transactions = $query->paginate(PAGINATION_COUNT_ADMIN);

        $mappedData = $transactions->getCollection()->map(function ($transaction) {
            $results = [];
            if ($transaction->order && $transaction->order->order_products->isNotEmpty()) {
                foreach ($transaction->order->order_products as $orderProduct) {
                    $results[] = [
                        'transaction_id' => $transaction->id,
                        'amount' => $transaction->amount,
                        'currency_code' => $transaction->currency_code,
                        'status' => $transaction->status,
                        'transaction_date' => $transaction->transaction_date,
                        'balance_log' => $transaction->balanceLog ? [
                            'balance_before' => $transaction->balanceLog->balance_before,
                            'balance_after' => $transaction->balanceLog->balance_after,
                            'balance_type' => $transaction->balanceLog->balance_type,
                        ] : null,
                        'quantity' => $orderProduct->quantity,
                        'product' => $orderProduct->product?->id,
                        'name' => $orderProduct->product?->name,
                    ];
                }
            } else {
                $results[] = [
                    'transaction_id' => $transaction->id,
                    'amount' => $transaction->amount,
                    'currency_code' => $transaction->currency_code,
                    'status' => $transaction->status,
                    'transaction_date' => $transaction->transaction_date,
                    'balance_log' => $transaction->balanceLog ? [
                        'balance_before' => $transaction->balanceLog->balance_before,
                        'balance_after' => $transaction->balanceLog->balance_after,
                        'balance_type' => $transaction->balanceLog->balance_type,
                    ] : null,
                    'quantity' => null,
                    'product' => null,
                    'name' => null,
                ];
            }
            return $results;
        })->collapse();

        return [
            'current_page' => $transactions->currentPage(),
            'data' => $mappedData,
            'first_page_url' => $transactions->url(1),
            'from' => $transactions->firstItem(),
            'last_page' => $transactions->lastPage(),
            'last_page_url' => $transactions->url($transactions->lastPage()),
            'links' => $transactions->linkCollection(),
            'next_page_url' => $transactions->nextPageUrl(),
            'path' => $transactions->path(),
            'per_page' => $transactions->perPage(),
            'prev_page_url' => $transactions->previousPageUrl(),
            'to' => $transactions->lastItem(),
            'total' => $transactions->total(),
        ];
    }



    public function mainPointReports($pos_terminal_id, $filter, $from = null, $to = null)
    {
        $query = PosTerminalTransaction::orderBy('created_at', 'desc')->with([
            'balanceLog',
            'order.order_products.product.translations'
        ])
            ->where('pos_terminal_id', $pos_terminal_id)
            ->whereHas('balanceLog', function ($q) {
                $q->where('balance_type', 'points');
            });

        switch ($filter) {
            case 'today':
                $query->whereDate('transaction_date', Carbon::today());
                break;
            case 'tomorrow':
                $query->whereDate('transaction_date', Carbon::tomorrow());
                break;
            case 'specific_date_range':
                if ($from && $to) {
                    $query->whereBetween('transaction_date', [Carbon::parse($from), Carbon::parse($to)]);
                }
                break;
            default:
                break;
        }

        $transactions = $query->paginate(PAGINATION_COUNT_ADMIN);

        $amount_per_points_redeem = SettingsHelper::getPointsCommissionSetting('amount_per_points_redeem');
        $points_per_amount_redeem = SettingsHelper::getPointsCommissionSetting('points_per_amount_redeem');
        $apply_on_recharging_by_mada = SettingsHelper::getPointsCommissionSetting('apply_on_recharging_by_mada');
        $points = 0;

        $mappedData = $transactions->getCollection()->map(function ($transaction) use ($amount_per_points_redeem, $points_per_amount_redeem, $apply_on_recharging_by_mada) {
            $results = [];
            // set balance log depend on setting
            if ($apply_on_recharging_by_mada) {

                if ( $transaction->amount >= $points_per_amount_redeem) {
                    // Calculate how many times the amount meets the redeem rule
                    $times = floor($transaction->amount / $points_per_amount_redeem);

                    // Total points based on the setting
                    $points = $times * $amount_per_points_redeem;
                }
            }
            if ($transaction->order && $transaction->order->order_products->isNotEmpty()) {
                foreach ($transaction->order->order_products as $orderProduct) {
                    $results[] = [
                        'transaction_id' => $transaction->id,
                        'amount' => $transaction->amount,
                        'points' => $points,
                        'currency_code' => $transaction->currency_code,
                        'status' => $transaction->status,
                        'transaction_date' => $transaction->transaction_date,
                        'balance_log' => $transaction->balanceLog ? [
                            'balance_before' => $transaction->balanceLog->balance_before,
                            'balance_after' => $transaction->balanceLog->balance_after,
                            'balance_type' => $transaction->balanceLog->balance_type,
                        ] : null,
                        'quantity' => $orderProduct->quantity,
                        'product' => $orderProduct->product?->id,
                        'name' => $orderProduct->product?->name,
                    ];
                }
            } else {
                $results[] = [
                    'transaction_id' => $transaction->id,
                    'amount' => $transaction->amount,
                    'points' => $points,
                    'currency_code' => $transaction->currency_code,
                    'status' => $transaction->status,
                    'transaction_date' => $transaction->transaction_date,
                    'balance_log' => $transaction->balanceLog ? [
                        'balance_before' => $transaction->balanceLog->balance_before,
                        'balance_after' => $transaction->balanceLog->balance_after,
                        'balance_type' => $transaction->balanceLog->balance_type,
                    ] : null,
                    'quantity' => null,
                    'product' => null,
                    'name' => 'شحن رصيد',
                ];
            }
            return $results;
        })->collapse();

        return [
            'current_page' => $transactions->currentPage(),
            'data' => $mappedData,
            'first_page_url' => $transactions->url(1),
            'from' => $transactions->firstItem(),
            'last_page' => $transactions->lastPage(),
            'last_page_url' => $transactions->url($transactions->lastPage()),
            'links' => $transactions->linkCollection(),
            'next_page_url' => $transactions->nextPageUrl(),
            'path' => $transactions->path(),
            'per_page' => $transactions->perPage(),
            'prev_page_url' => $transactions->previousPageUrl(),
            'to' => $transactions->lastItem(),
            'total' => $transactions->total(),
        ];
    }




    public function balanceRequestReports(string $pos_terminal_id, mixed $filter, ?string $status, mixed $from, mixed $to)
    {
        $query = BalanceRequest::with(['distributor', 'posTerminal'])
            ->where('pos_terminal_id', $pos_terminal_id);

        // Apply status filter
        if ($status && in_array($status, BalanceRequestStatusEnum::getList())) {
            $query->where('status', $status);
        }

        // Apply date filters
        switch ($filter) {
            case 'today':
                $query->whereDate('created_at', Carbon::today());
                break;

            case 'tomorrow':
                $query->whereDate('created_at', Carbon::tomorrow());
                break;

            case 'specific_date_range':
                if ($from && $to) {
                    $query->whereBetween('created_at', [Carbon::parse($from), Carbon::parse($to)]);
                }
                break;

            default:
                break;
        }

        return $query->paginate(PAGINATION_COUNT_ADMIN);
    }




    public function model()
    {
        return DistributorPosTerminal::class;
    }


}
