<?php

namespace App\Services\BalanceRequest;

use App\Adapters\PaymentGateways\MadaTransactionAdapter;
use App\DTO\Admin\BalanceRequest\BalanceRequestStatusDto;
use App\DTO\BaseDTO;
use App\DTO\Pos\BalanceRequest\BalanceRequestDto;
use App\DTO\Pos\BalanceRequest\MadaCallbackDto;
use App\Enums\BalanceLog\BalanceTypeStatusEnum;
use App\Enums\BalanceRequest\BalanceRequestStatusEnum;
use App\Enums\PosTerminalTransaction\TransactionStatusEnum;
use App\Enums\PosTerminalTransaction\TransactionTypeEnum;
use App\Helpers\SettingsHelper;
use App\Repositories\BalanceLog\BalanceLogRepository;
use App\Helpers\UniqueCodeGeneratorHelper;
use App\Http\Resources\Admin\BalanceRequest\BalanceRequestResource;
use App\Interfaces\ServicesInterfaces\BalanceRequest\BalanceRequestServiceInterface;
use App\Models\BalanceRequest\BalanceRequest;
use App\Repositories\Distributor\DistributorPosTerminalRepository;
use App\Repositories\PosTerminal\BalanceRequestRepository;
use App\Repositories\PosTerminal\PosTerminalRepository;
use App\Repositories\PosTerminal\PosTerminalTransactionRepository;
use App\Services\BaseService;
use Illuminate\Support\Facades\DB;

class BalanceRequestService extends BaseService implements BalanceRequestServiceInterface
{

    public function __construct(private readonly BalanceRequestRepository         $balanceRequestRepository,
                                private readonly PosTerminalTransactionRepository $posTerminalTransactionRepository,
                                private readonly DistributorPosTerminalRepository $distributorPosTerminalRepository,
                                private readonly PosTerminalRepository            $posTerminalRepository,
                                private readonly BalanceLogRepository             $balanceLogRepository,
    )
    {
    }

    public function index(array $filter): mixed
    {
        $requests = $this->balanceRequestRepository;
        if (request()->filled('status')) {
            $requests = $requests->where('status', request('status'));
        }
        return $this->ApiSuccessResponse(BalanceRequestResource::collection($requests->paginate())->response()->getData());
    }

    public function show($id): mixed
    {

    }

    public function store(BalanceRequestDto|BaseDTO $data): mixed
    {
        try {
            DB::beginTransaction();
            $pos = auth('posApi')->user();
            $input = $data->toArray();
            $input['distributor_id'] = $pos->distributor_id;
            $input['pos_terminal_id'] = $pos->pos_terminal_id;
            $input['pos_name'] = $pos?->posTerminal?->name;
            $code = UniqueCodeGeneratorHelper::generateDigitsWithDate(8, BalanceRequest::class, 'code');
            $input['code'] = $code;
            $input['status'] = $data->getIsMada() ? TransactionStatusEnum::DRAFT->value : TransactionStatusEnum::PENDING->value;
            $balance_request = $this->balanceRequestRepository->create($input);

            if ($data->getIsMada()) {
                // Create Draft Transaction
                $transaction = $this->posTerminalTransactionRepository->saveBalanceRequestTransaction($balance_request, TransactionStatusEnum::DRAFT);
                $this->balanceRequestRepository->update(["transaction_id" => $transaction->id], $balance_request->id);
            }
            DB::commit();
            return $this->ApiSuccessResponse(['payment_code' => $balance_request->code]);
        } catch (\Exception $exception) {
            return $this->ApiErrorResponse($exception->getMessage(), __('admin.general_error'));
        }
    }

    public function update($id, BalanceRequestStatusDto|BaseDTO $data): mixed
    {
        try {
            DB::beginTransaction();
            $input = $data->toArray();
            $balance_request = $this->balanceRequestRepository->find($id);
            /*if ($balance_request->status == BalanceRequestStatusEnum::ACCEPTED) {
                return $this->ApiErrorResponse("Already accepted");
            }*/

            if (!$balance_request) {
                return $this->ApiErrorResponse("invalid request");
            }
            $this->balanceRequestRepository->update(["status" => $input['status']], $id);

            // Save transaction if accepted
            if ($input['status'] == BalanceRequestStatusEnum::ACCEPTED) {
                $transaction = $this->posTerminalTransactionRepository->saveBalanceRequestTransaction($balance_request);
                // Update transaction_id column
                $this->balanceRequestRepository->update(["transaction_id" => $transaction->id], $id);
                // Update Pos Balance
                $pos_terminal = $this->posTerminalRepository->find($balance_request->pos_terminal_id);
                $new_balance = $pos_terminal->balance + $balance_request->amount;
                $this->posTerminalRepository->update(["balance" => $new_balance], $balance_request->pos_terminal_id);

                // Update Distributor POS Balance
                $distributor_pos_terminal = $this->distributorPosTerminalRepository->getByPosTerminalID($pos_terminal->id);
                $distributor_pos_terminal->update(['balance' => $new_balance]);
            }

            DB::commit();

            return $this->ApiSuccessResponse();

        } catch (\Exception $exception) {
            return $this->ApiErrorResponse($exception->getMessage(), __('admin.general_error'));
        }
    }

    public function delete($id): mixed
    {

        return $this->ApiSuccessResponse("", "Deleted Successfully");
    }

    public function getTrashed()
    {
        // TODO: Implement getTrashed() method.
    }

    public function restore($id)
    {
        // TODO: Implement restore() method.
    }

    public function bulkDelete(array $ids = [])
    {
        // TODO: Implement bulkDelete() method.
    }

    public function madaCallback(MadaCallbackDto $dto)
    {
        try {
            DB::beginTransaction();

            $mada_object = new MadaTransactionAdapter($dto->getPaymentGatewayResponse());
            $balance_request = $this->balanceRequestRepository->getRequestByPaymentCode($dto->getPaymentCode(), $mada_object->getAmount());

            if (!$balance_request) {
                return $this->ApiErrorResponse('Invalid transaction data');
            }

            if ($balance_request->status == BalanceRequestStatusEnum::ACCEPTED) {
                return $this->ApiErrorResponse('Already accepted');
            }

            if ($mada_object->getStatusCode() != 00) {
                return $this->ApiErrorResponse('Cannot continue with invalid status code');
            }

            $this->balanceRequestRepository->update(["status" => BalanceRequestStatusEnum::ACCEPTED], $balance_request->id);

            // Save transaction if accepted
            $transaction = $this->posTerminalTransactionRepository->update([
                'status' => TransactionStatusEnum::SUCCESS->value,
                'payment_method' => 'mada',
                'transaction_object' => $dto->getPaymentGatewayResponse(),
                'reference_number' => $mada_object->getRRN()
            ], $balance_request->transaction_id);

            // Update Pos Balance
            $pos_terminal = $this->posTerminalRepository->find($balance_request->pos_terminal_id);
            $new_balance = $pos_terminal->balance + $balance_request->amount;


            $amount_per_points_redeem = SettingsHelper::getPointsCommissionSetting('amount_per_points_redeem');
            $points_per_amount_redeem = SettingsHelper::getPointsCommissionSetting('points_per_amount_redeem');
            $apply_on_recharging_by_mada = SettingsHelper::getPointsCommissionSetting('apply_on_recharging_by_mada');
            $points = 0;
            // set balance log depend on setting
            if ($apply_on_recharging_by_mada) {
                $distributor_pos_terminal_Data= $this->distributorPosTerminalRepository->getByPosTerminalID($pos_terminal->id);
                if ($balance_request->amount >= $points_per_amount_redeem) {
                    // Calculate how many times the amount meets the redeem rule
                    $times = floor($balance_request->amount / $points_per_amount_redeem);

                    // Total points based on the setting
                    $points = $times * $amount_per_points_redeem;

                    // Log points in the balance log
                    $this->balanceLogRepository->create([
                        'transaction_id' => $balance_request->transaction_id,
                        'distributor_id' => $balance_request->distributor_id,
                        'pos_terminal_id' => $balance_request->pos_terminal_id,
                        'amount' => $points, // Log the calculated points instead of amount
                        'balance_type' => 'points',
                        'balance_before' => $distributor_pos_terminal_Data->points,
                        'balance_after' => $distributor_pos_terminal_Data->points +  $points,
                        'transaction_type' => TransactionTypeEnum::CREDIT->value
                    ]);
                }
            }
            $this->posTerminalRepository->update(["balance" => $new_balance], $pos_terminal->id);

            // Update Distributor POS Balance
            $distributor_pos_terminal = $this->distributorPosTerminalRepository->getByPosTerminalID($pos_terminal->id);
            $distributor_pos_terminal = $distributor_pos_terminal->update(['balance' => $new_balance,'points' => $distributor_pos_terminal->points + $points]);

            DB::commit();

            return $this->ApiSuccessResponse([
                'balance_before' => $transaction->balance_before,
                'amount' => $mada_object->getAmount(),
                'bank_commission' => 1.15,
                'current_balance' => $transaction->balance_after - 1.15,
            ]);

        } catch (\Exception $exception) {
            return $this->ApiErrorResponse($exception->getTrace(), __('admin.general_error'));
        }
    }
}
