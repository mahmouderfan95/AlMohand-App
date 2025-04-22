<?php

namespace App\Services\Report;

use App\Http\Resources\Admin\PosTerminal\PosTerminalTransactionsResource;
use App\Http\Resources\Seller\OrderProductResource;
use App\Http\Resources\Seller\OrderResource;
use App\Interfaces\ServicesInterfaces\Report\ReportServiceInterface;

use App\Repositories\Report\ReportRepository;
use App\Services\BaseService;
use App\Traits\ApiResponseAble;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReportService extends BaseService implements ReportServiceInterface
{
    use ApiResponseAble;

    private $reportRepository;

    public function __construct(ReportRepository $reportRepository)
    {
        $this->reportRepository = $reportRepository;
    }

    public function orderReports(Request $request, $distributor_pos_terminal): mixed
    {
        try {
            $filter = $request->input('filter');
            $from = $request->input('from', null);
            $to = $request->input('to', null);
            $payment_method = $request->input('payment_method', null);

            $order_reports = $this->reportRepository->orderReports($distributor_pos_terminal, $filter, $from, $to, $payment_method);
            return $this->ApiSuccessResponse(OrderResource::collection($order_reports)->resource);

        } catch (Exception $e) {
            return $this->ApiErrorResponse(__('admin.general_error'), $e->getMessage());
        }
    }

    public function balanceReports(Request $request, $distributor_pos_terminal): mixed
    {
        try {
            $filter = $request->input('filter', 'today');
            $from = $request->input('from', null);
            $to = $request->input('to', null);

            $balanceReports = $this->reportRepository->balanceReports($distributor_pos_terminal, $filter, $from, $to);
            return $this->ApiSuccessResponse($balanceReports);

        } catch (Exception $e) {
            return $this->ApiErrorResponse(__('admin.general_error'), $e->getMessage());
        }
    }

    public function commissionReports(Request $request, $user): mixed
    {

        try {
            $filter = $request->input('filter', 'today');
            $from = $request->input('from', null);
            $to = $request->input('to', null);

            $balanceReports = $this->reportRepository->commissionReports('59370a22-49a8-4ef4-aafd-3176211b0828', $filter, $from, $to);
            return $this->ApiSuccessResponse($balanceReports);

        } catch (Exception $e) {
            return $this->ApiErrorResponse(__('admin.general_error'), $e->getMessage());
        }
    }


    public function balanceRequestReports(Request $request, $user): mixed
    {
        try {
            $filter = $request->input('filter', 'today');
            $status = $request->input('status', null);
            $from = $request->input('from', null);
            $to = $request->input('to', null);

            $balanceReports = $this->reportRepository->balanceRequestReports(
                '59370a22-49a8-4ef4-aafd-3176211b0828',
                $filter,
                $status,
                $from,
                $to
            );

            return $this->ApiSuccessResponse($balanceReports);
        } catch (Exception $e) {
            return $this->ApiErrorResponse(__('admin.general_error'), $e->getMessage());
        }
    }


    public function pointReports(Request $request, $user): mixed
    {

        try {
            $filter = $request->input('filter');
            $from = $request->input('from', null);
            $to = $request->input('to', null);

            $balanceReports = $this->reportRepository->pointReports('59370a22-49a8-4ef4-aafd-3176211b0828', $filter, $from, $to);
            return $this->ApiSuccessResponse($balanceReports);

        } catch (Exception $e) {
            return $this->ApiErrorResponse(__('admin.general_error'), $e->getMessage());
        }
    }

    public function mainCommissionReports(Request $request, $user): mixed
    {

        try {
            $filter = $request->input('filter');
            $from = $request->input('from', null);
            $to = $request->input('to', null);

            $balanceReports = $this->reportRepository->mainCommissionReports('59370a22-49a8-4ef4-aafd-3176211b0828', $filter, $from, $to);
            return $this->ApiSuccessResponse($balanceReports);

        } catch (Exception $e) {
            return $this->ApiErrorResponse(__('admin.general_error'), $e->getMessage());
        }
    }

    public function mainPointReports(Request $request, $user): mixed
    {

        try {
            $filter = $request->input('filter');
            $from = $request->input('from', null);
            $to = $request->input('to', null);

            $balanceReports = $this->reportRepository->mainPointReports('59370a22-49a8-4ef4-aafd-3176211b0828', $filter, $from, $to);
            return $this->ApiSuccessResponse($balanceReports);

        } catch (Exception $e) {
            return $this->ApiErrorResponse(__('admin.general_error'), $e->getMessage());
        }
    }


}
