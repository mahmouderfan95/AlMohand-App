<?php

namespace App\Services\PosTerminal;

use App\DTO\Admin\Merchant\CreateMerchantGroupDto;
use App\DTO\Admin\Merchant\GetDistributorTransactionsDto;
use App\DTO\Admin\Merchant\UpdateBalanceDto;
use App\DTO\Admin\PosTerminal\CreatePosTerminalDto;
use App\DTO\Admin\PosTerminal\GetPosTerminalTransactionsDto;
use App\DTO\BaseDTO;
use App\Enums\Distributor\DistributorConditionPrefixEnum;
use App\Enums\Distributor\DistributorConditionTypeEnum;
use App\Enums\PosTerminalTransaction\TransactionReflectionEnum;
use App\Enums\PosTerminalTransaction\TransactionTypeEnum;
use App\Helpers\PosNameGenerator;
use App\Http\Resources\Admin\Distributor\DistributorDetailsResource;
use App\Http\Resources\Admin\Distributor\DistributorGroupResource;
use App\Http\Resources\Admin\Distributor\DistributorPosTerminalListResource;
use App\Http\Resources\Admin\Distributor\DistributorResource;
use App\Http\Resources\Admin\PosTerminal\PosTerminalResource;
use App\Http\Resources\Admin\PosTerminal\PosTerminalTransactionsResource;
use App\Interfaces\ServicesInterfaces\Distributor\DistributorGroupServiceInterface;
use App\Interfaces\ServicesInterfaces\PosTerminal\PosTerminalServiceInterface;
use App\Interfaces\ServicesInterfaces\PosTerminal\PosTerminalTransactionServiceInterface;
use App\Models\POSTerminal\PosTerminal;
use App\Repositories\Distributor\DistributorGroupConditionRepository;
use App\Repositories\Distributor\DistributorGroupRepository;
use App\Repositories\Distributor\DistributorPosTerminalRepository;
use App\Repositories\Distributor\DistributorRepository;
use App\Repositories\PosTerminal\PosTerminalRepository;
use App\Repositories\PosTerminal\PosTerminalTransactionRepository;
use App\Services\BaseService;
use Illuminate\Support\Facades\DB;

class PosTerminalTransactionService extends BaseService implements PosTerminalTransactionServiceInterface
{

    public function __construct(private readonly PosTerminalTransactionRepository $posTerminalTransactionRepository,
                                private readonly DistributorRepository            $distributorRepository,
                                private readonly DistributorPosTerminalRepository $distributorPosTerminalRepository,
                                private readonly PosTerminalRepository            $posTerminalRepository
    )
    {
    }

    public function index(array $filter): mixed
    {
    }

    public function show($id): mixed
    {

    }

    public function store(CreatePosTerminalDto|BaseDTO $data): mixed
    {
        try {
            DB::beginTransaction();
            DB::commit();
            return $this->ApiSuccessResponse();
        } catch (\Exception $exception) {
            return $this->ApiErrorResponse($exception->getMessage(), __('admin.general_error'));
        }
    }

    public function update($id, CreatePosTerminalDto|BaseDTO $data): mixed
    {
        try {
            DB::beginTransaction();
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

    public function generateName(): \Illuminate\Http\JsonResponse
    {
        return $this->ApiSuccessResponse(['name' => PosNameGenerator::generate()]);
    }

    public function getPosTransactionsList(GetPosTerminalTransactionsDto $dto): mixed
    {
        $filters = [
            "amount" => $dto->getAmount(),
            "description" => $dto->getDescription(),
            "type" => $dto->getType(),
        ];
        $transactions = $this->posTerminalTransactionRepository->getPosTransactions($dto->getMerchantId(), $dto->getPosTerminalId(), $filters);
        return $this->ApiSuccessResponse(PosTerminalTransactionsResource::collection($transactions)->response()->getData());
    }

    public function getPosSalesTransactions($pos_terminal_id, GetPosTerminalTransactionsDto $dto): mixed
    {
        $pos_terminal = $this->posTerminalRepository->find($pos_terminal_id);

        if (!$pos_terminal) {
            return $this->ApiErrorResponse("invalid pos id");
        }

        $filters = [];
        $transactions = $this->posTerminalTransactionRepository->getPosSalesTransactions($pos_terminal_id, $dto->getMerchantId(), $filters);
        return $this->ApiSuccessResponse(PosTerminalTransactionsResource::collection($transactions)->response()->getData());
    }

    public function getPosBalanceTransactions($pos_terminal_id, GetPosTerminalTransactionsDto $dto): mixed
    {
        $pos_terminal = $this->posTerminalRepository->find($pos_terminal_id);

        if (!$pos_terminal) {
            return $this->ApiErrorResponse("invalid pos id");
        }

        $filters = [
            "amount" => $dto->getAmount(),
            "description" => $dto->getDescription(),
            "type" => $dto->getType(),
        ];
        $transactions = $this->posTerminalTransactionRepository->getPosBalanceTransactions($pos_terminal_id, $dto->getMerchantId(), $filters);
        return $this->ApiSuccessResponse(PosTerminalTransactionsResource::collection($transactions)->response()->getData());
    }

    public function getDistributorBalanceTransactions($distributor_id, GetDistributorTransactionsDto $dto): mixed
    {
        $distributor = $this->distributorRepository->find($distributor_id);

        if (!$distributor) {
            return $this->ApiErrorResponse("invalid distributor id");
        }

        $transactions = $this->posTerminalTransactionRepository->getDistributorTransactions($distributor_id, TransactionReflectionEnum::BALANCE);
        return $this->ApiSuccessResponse(PosTerminalTransactionsResource::collection($transactions)->response()->getData());
    }

    public function getDistributorCommissionTransactions($distributor_id, GetDistributorTransactionsDto $dto): mixed
    {
        $filters = [];
        $transactions = $this->posTerminalTransactionRepository->getDistributorTransactions($distributor_id, TransactionReflectionEnum::COMMISSION, $filters);
        return $this->ApiSuccessResponse(PosTerminalTransactionsResource::collection($transactions)->response()->getData());
    }

    public function updateDistributorBalance($distributor_id, UpdateBalanceDto $dto)
    {
        try {
            DB::beginTransaction();
            $distributor = $this->distributorRepository->find($distributor_id);

            if (!$distributor) {
                return $this->ApiErrorResponse("invalid distributor id");
            }

            $transaction = $this->posTerminalTransactionRepository->updateDistributorBalance($distributor, $dto);

            $distributor = $this->distributorRepository->update(['balance' => $transaction->balance_after], $distributor_id);
            DB::commit();

            return $this->ApiSuccessResponse(new DistributorDetailsResource($distributor));

        } catch (\Exception $exception) {
            return $this->ApiErrorResponse($exception->getMessage(), __('admin.general_error'));
        }
    }

    public function updatePosBalance($distributor_pos_terminal_id, UpdateBalanceDto $dto)
    {
        try {
            DB::beginTransaction();

            $pos_terminal = $this->distributorPosTerminalRepository->find($distributor_pos_terminal_id);

            if (!$pos_terminal) {
                return $this->ApiErrorResponse("invalid pos id");
            }

            $distributor = $this->distributorRepository->find($pos_terminal->distributor_id);

            // Validate Merchant Balance
            if ($dto->getType() == 'credit' && $distributor->balance < $dto->getAmount()) {
                return $this->ApiErrorResponse([], "Insufficient balance at merchant side");
            }

            if ($dto->getType() == 'debit' && $pos_terminal->balance < $dto->getAmount()) {
                return $this->ApiErrorResponse([], "Insufficient balance at POS side");
            }


            $transaction = $this->posTerminalTransactionRepository->updatePosTerminalBalance($pos_terminal, $dto);
            // Update POS Balance
            $pos_terminal = $this->distributorPosTerminalRepository->update(['balance' => $transaction->balance_after], $distributor_pos_terminal_id);
            // Update Distributor Balance
            $dto->getType() == 'credit' ? $dto->setType(TransactionTypeEnum::DEBIT) : $dto->setType(TransactionTypeEnum::CREDIT);
            $distributor_transaction = $this->posTerminalTransactionRepository->updateDistributorBalance($distributor, $dto);
            $this->distributorRepository->update(['balance' => $distributor_transaction->balance_after], $distributor->id);
            DB::commit();

            return $this->ApiSuccessResponse(new DistributorPosTerminalListResource($pos_terminal));

        } catch (\Exception $exception) {
            return $this->ApiErrorResponse($exception->getMessage(), __('admin.general_error'));
        }
    }
}
