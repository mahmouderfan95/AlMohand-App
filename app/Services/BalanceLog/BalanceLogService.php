<?php

namespace App\Services\BalanceLog;

use App\DTO\Admin\BalanceRequest\BalanceRequestStatusDto;
use App\DTO\Admin\Merchant\UpdateBalanceDto;
use App\DTO\Admin\PosTerminal\CreatePosTerminalDto;
use App\DTO\Admin\PosTerminal\GetPosTerminalTransactionsDto;
use App\DTO\BaseDTO;
use App\DTO\Pos\BalanceRequest\BalanceRequestDto;
use App\Enums\BalanceLog\BalanceTypeStatusEnum;
use App\Enums\BalanceRequest\BalanceRequestStatusEnum;
use App\Enums\PosTerminalTransaction\TransactionTypeEnum;
use App\Helpers\PosNameGenerator;
use App\Helpers\UniqueCodeGeneratorHelper;
use App\Http\Resources\Admin\BalanceLog\PosPointsBalanceLogResource;
use App\Http\Resources\Admin\BalanceRequest\BalanceRequestResource;
use App\Http\Resources\Admin\PosTerminal\PosTerminalTransactionsResource;
use App\Http\Resources\SalesRep\BalanceLog\PosCommissionsTransactionsResource;
use App\Http\Resources\SalesRep\BalanceLog\PosPointsTransactionsResource;
use App\Interfaces\ServicesInterfaces\BalanceLog\BalanceLogServiceInterface;
use App\Interfaces\ServicesInterfaces\BalanceRequest\BalanceRequestServiceInterface;
use App\Interfaces\ServicesInterfaces\PosTerminal\PosTerminalTransactionServiceInterface;
use App\Models\BalanceRequest\BalanceRequest;
use App\Repositories\BalanceLog\BalanceLogRepository;
use App\Repositories\Distributor\DistributorPosTerminalRepository;
use App\Repositories\PosTerminal\BalanceRequestRepository;
use App\Repositories\PosTerminal\PosTerminalRepository;
use App\Repositories\PosTerminal\PosTerminalTransactionRepository;
use App\Services\BaseService;
use Illuminate\Support\Facades\DB;
use Prettus\Validator\Exceptions\ValidatorException;

class BalanceLogService extends BaseService implements BalanceLogServiceInterface
{

    public function __construct(private readonly BalanceLogRepository $balanceLogRepository,
                                private readonly PosTerminalTransactionRepository $posTerminalTransactionRepository,
                                private readonly DistributorPosTerminalRepository $distributorPosTerminalRepository,
                                private readonly PosTerminalRepository $posTerminalRepository,
    )
    {
    }

    public function index(array $filter): mixed
    {

    }

    public function show($id): mixed
    {

    }

    public function store(BalanceRequestDto|BaseDTO $data): mixed
    {

    }

    public function update($id, BalanceRequestStatusDto|BaseDTO $data): mixed
    {
        try {
            DB::beginTransaction();


            DB::commit();

            return $this->ApiSuccessResponse();

        }catch (\Exception $exception) {
            return $this->ApiErrorResponse($exception->getMessage(), __('admin.general_error'));
        }
    }

    public function delete($id): mixed
    {
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

    public function redeem($pos_terminal_id, $type, $amount)
    {
        try {
            // Validate POS terminal and distributor
            $distributor_pos_terminal = $this->validatePosTerminal($pos_terminal_id);
            if (!$distributor_pos_terminal) {
                return $this->ApiErrorResponse("Invalid POS");
            }

            if (!$this->validateSufficientBalance($distributor_pos_terminal, $type, $amount)) {
                return $this->ApiErrorResponse("Invalid amount");
            }

            if ($type == BalanceTypeStatusEnum::POINTS->value && $amount < 100) {
                return $this->ApiErrorResponse("Amount should be greater than the minimum points to redeem");
            }

            // Redeem Based on Redemption Type
            DB::beginTransaction();

            switch ($type) {
                case BalanceTypeStatusEnum::POINTS->value:
                    $response = $this->redeemPoints($distributor_pos_terminal, $pos_terminal_id, $amount);
                    break;

                case BalanceTypeStatusEnum::COMMISSION->value:
                    $response = $this->redeemCommission($distributor_pos_terminal, $pos_terminal_id, $amount);
                    break;

                default:
                    return $this->ApiErrorResponse("Invalid type");
            }

            DB::commit();

            return $this->ApiSuccessResponse($response);

        } catch (\Exception $exception) {
            DB::rollBack();
            return $this->ApiErrorResponse($exception->getMessage(), __('admin.general_error'));
        }
    }

    private function validatePosTerminal($pos_terminal_id)
    {
        return $this->distributorPosTerminalRepository->findWhere(['pos_terminal_id' => $pos_terminal_id])->first();
    }

    private function validateSufficientBalance($distributor_pos_terminal, $type, $amount): bool
    {
        if ($type === BalanceTypeStatusEnum::POINTS->value) {
            return $distributor_pos_terminal->points >= $amount;
        }

        if ($type === BalanceTypeStatusEnum::COMMISSION->value) {
            return $distributor_pos_terminal->commission >= $amount;
        }

        return false;
    }

    /**
     * @throws ValidatorException
     */
    private function redeemPoints($distributor_pos_terminal, $pos_terminal_id, $amount): array
    {
        $points_balance_after = $distributor_pos_terminal->points - $amount;
        $cashbackValue = $this->getPointsCashbackValue($amount);

        $balance_log = $this->logBalanceChange(
            $distributor_pos_terminal, $pos_terminal_id, $amount, $points_balance_after,
            BalanceTypeStatusEnum::POINTS->value
        );

        $this->updateDistributorPosTerminalBalance(
            $distributor_pos_terminal, ['points' => $points_balance_after, 'balance' => $distributor_pos_terminal->balance + $cashbackValue]
        );

        $transaction = $this->createTransaction($distributor_pos_terminal, TransactionTypeEnum::CREDIT->value, $cashbackValue);

        $this->updatePosTerminalBalance($pos_terminal_id, $cashbackValue);
        $this->updateBalanceLogWithTransactionId($transaction, $balance_log->id);

        return [
            'balance_before' => $distributor_pos_terminal->balance,
            'redeemed_amount' => $cashbackValue,
            'current_balance' => $distributor_pos_terminal->balance + $cashbackValue,
        ];
    }

    /**
     * @throws ValidatorException
     */
    private function redeemCommission($distributor_pos_terminal, $pos_terminal_id, $amount): array
    {
        $commission_balance_after = $distributor_pos_terminal->commission - $amount;

        $balance_log = $this->logBalanceChange(
            $distributor_pos_terminal, $pos_terminal_id, $amount, $commission_balance_after,
            BalanceTypeStatusEnum::COMMISSION->value
        );

        $this->updateDistributorPosTerminalBalance(
            $distributor_pos_terminal, ['commission' => $commission_balance_after, 'balance' => $distributor_pos_terminal->balance + $amount]
        );

        $transaction = $this->createTransaction($distributor_pos_terminal, TransactionTypeEnum::CREDIT->value, $amount);

        $this->updatePosTerminalBalance($pos_terminal_id, $amount);
        $this->updateBalanceLogWithTransactionId($transaction, $balance_log->id);

        return [
            'balance_before' => $distributor_pos_terminal->balance,
            'redeemed_amount' => $amount,
            'current_balance' => $distributor_pos_terminal->balance + $amount,
        ];
    }

    /**
     * @throws ValidatorException
     */
    private function logBalanceChange($distributor_pos_terminal, $pos_terminal_id, $amount, $balance_after, $type)
    {
        return $this->balanceLogRepository->create([
            'distributor_id' => $distributor_pos_terminal->distributor_id,
            'pos_terminal_id' => $pos_terminal_id,
            'amount' => $amount,
            'balance_type' => $type,
            'balance_before' => $distributor_pos_terminal->{$type === BalanceTypeStatusEnum::POINTS->value ? 'points' : 'commission'},
            'balance_after' => $balance_after,
            'transaction_type' => TransactionTypeEnum::DEBIT->value
        ]);
    }

    /**
     * @throws ValidatorException
     */
    private function updateDistributorPosTerminalBalance($distributor_pos_terminal, $data): void
    {
        $this->distributorPosTerminalRepository->update($data, $distributor_pos_terminal->id);
    }

    private function createTransaction($distributor_pos_terminal, $type, $amount)
    {
        return $this->posTerminalTransactionRepository->updatePosTerminalBalance($distributor_pos_terminal, ['type' => $type, 'amount' => $amount]);
    }

    /**
     * @throws ValidatorException
     */
    private function updatePosTerminalBalance($pos_terminal_id, $amount): void
    {
        $pos_terminal = $this->posTerminalRepository->find($pos_terminal_id);
        $this->posTerminalRepository->update(['balance' => $pos_terminal->balance + $amount], $pos_terminal_id);
    }

    /**
     * @throws ValidatorException
     */
    private function updateBalanceLogWithTransactionId($transaction, $balance_log_id): void
    {
        $this->balanceLogRepository->update(['transaction_id' => $transaction->id], $balance_log_id);
    }


    public function getPointsCashbackValue($points): float
    {
        // Will be coming from config
        return floor($points / 100);
    }

    public function getPosPointsTransactions($pos_terminal_id)
    {
        try {
            // Validate POS terminal and distributor
            $pos_terminal = $this->posTerminalRepository->find($pos_terminal_id);
            if (!$pos_terminal) {
                return $this->ApiErrorResponse("Invalid POS");
            }

            $balance_logs = $this->balanceLogRepository->getPosTerminalPointsLogs($pos_terminal);

            DB::commit();

            return $this->ApiSuccessResponse(PosPointsBalanceLogResource::collection($balance_logs));

        } catch (\Exception $exception) {
            DB::rollBack();
            return $this->ApiErrorResponse($exception->getMessage(), __('admin.general_error'));
        }
    }

    public function getPointsTransactionsByDistributorPosTerminal($distributor_pos_terminal_id)
    {
        try {
            // Validate POS terminal and distributor
            $distributor_pos_terminal = $this->distributorPosTerminalRepository->find($distributor_pos_terminal_id);
            if (! $distributor_pos_terminal) {
                return $this->ApiErrorResponse("Invalid Distributor POS");
            }
            $balance_logs = $this->balanceLogRepository->getPosTerminalPointsLogs($distributor_pos_terminal->pos_terminal_id);

            DB::commit();

            return $this->ApiSuccessResponse(PosPointsTransactionsResource::collection($balance_logs)->response()->getData());

        } catch (\Exception $exception) {
            DB::rollBack();
            return $this->ApiErrorResponse($exception->getMessage(), __('admin.general_error'));
        }
    }

    public function getCommissionTransactionsByDistributorPosTerminal($distributor_pos_terminal_id)
    {
        try {
            // Validate POS terminal and distributor
            $distributor_pos_terminal = $this->distributorPosTerminalRepository->find($distributor_pos_terminal_id);
            if (! $distributor_pos_terminal) {
                return $this->ApiErrorResponse("Invalid Distributor POS");
            }
            $balance_logs = $this->balanceLogRepository->getPosTerminalCommissionsLogs($distributor_pos_terminal->pos_terminal_id);

            DB::commit();

            return $this->ApiSuccessResponse(PosCommissionsTransactionsResource::collection($balance_logs)->response()->getData());

        } catch (\Exception $exception) {
            DB::rollBack();
            return $this->ApiErrorResponse($exception->getMessage(), __('admin.general_error'));
        }
    }
}
