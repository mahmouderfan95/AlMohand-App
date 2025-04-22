<?php

namespace App\Services\Report;

use App\DTO\Admin\Report\SalesReportDto;
use App\DTO\Pos\Order\AllOrdersDto;
use App\Enums\Order\OrderPaymentMethod;
use App\Http\Resources\Admin\Report\SalesReport\DailySalesReportResource;
use App\Http\Resources\Admin\Report\SalesReport\TotalProfitReportResource;
use App\Interfaces\ServicesInterfaces\Report\SalesReportServiceInterface;
use App\Repositories\Order\OrderProductSerialRepository;
use App\Services\BaseService;
use Exception;
use Illuminate\Support\Facades\DB;

class SalesReportService extends BaseService implements SalesReportServiceInterface
{

    public function __construct(private readonly OrderProductSerialRepository $orderProductSerialRepository)
    {

    }

    public function getDailySalesReport(SalesReportDto $dto)
    {
        try {
            DB::beginTransaction();

            $data = $dto->toArray();

            $orders = $this->orderProductSerialRepository->getDailySalesReport($data);

            $response = [
                'total_cards' => $this->orderProductSerialRepository->getTotalOrderedCardsQuantity($data),
                'total_balance_transactions' => number_format($this->orderProductSerialRepository->getTotalOrdersAmountWihPaymentMethod($data, OrderPaymentMethod::getBalance()), 3, '.', ''),
                'total_mada_transactions' => number_format($this->orderProductSerialRepository->getTotalOrdersAmountWihPaymentMethod($data, OrderPaymentMethod::getMada()), 3, '.', ''),
                'data' => (DailySalesReportResource::collection($orders)->response()->getData())
            ];

            DB::commit();

            return $this->ApiSuccessResponse($response);

        } catch(Exception $e){
            DB::rollBack();
            return $this->ApiErrorResponse($e->getMessage(), trans('admin.general_error'));
        }
    }

    public function getProfitReport(SalesReportDto $dto)
    {
        try {
            DB::beginTransaction();

            $data = $dto->toArray();

            $orders = $this->orderProductSerialRepository->getTotalProfitReport($data);

            $response = [
                'total_profit' => $orders['total_profit'],
                'total_sales' => $orders['total_sales'],
                'total_cost' => $orders['total_cost'],
                'data' => (TotalProfitReportResource::collection($orders['orders'])->response()->getData())
            ];

            DB::commit();

            return $this->ApiSuccessResponse($response);

        } catch(Exception $e){
            DB::rollBack();
            return $this->ApiErrorResponse($e->getMessage(), trans('admin.general_error'));
        }
    }

    public function getProfitDetailsReport(SalesReportDto $dto)
    {
        // TODO: Implement getProfitDetailsReport() method.
    }
}
