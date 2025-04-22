<?php

namespace App\Repositories\PosTerminal;

use App\DTO\Admin\Merchant\UpdateBalanceDto;
use App\Enums\PosTerminalTransaction\TransactionCreatedByTypeEnum;
use App\Enums\PosTerminalTransaction\TransactionReflectionEnum;
use App\Enums\PosTerminalTransaction\TransactionStatusEnum;
use App\Enums\PosTerminalTransaction\TransactionTypeEnum;
use App\Helpers\UniqueCodeGeneratorHelper;
use App\Models\BalanceRequest\BalanceRequest;
use App\Models\Distributor\Distributor;
use App\Models\POSTerminal\PosTerminal;
use App\Models\POSTerminal\PosTerminalTransaction;
use App\Repositories\BaseRepository;
use Illuminate\Container\Container as Application;
use Illuminate\Support\Str;


class PosTerminalTransactionRepository extends BaseRepository
{

    public function __construct(Application $app)
    {
        parent::__construct($app);
    }

    public function getPosTransactions($distributor_id, $pos_terminal_id, $filters = [])
    {
        $query = $this->model->query()->where('distributor_id', '=', $distributor_id)
            ->where('pos_terminal_id', '=', $pos_terminal_id);

        $this->applyTransactionsFilter($filters, $query);

        if (!request('isPaginate')) {
            $query = $query->get();
        } else {
            $query = $query->paginate();
        }

        return $query;
    }

    public function getPosSalesTransactions($pos_id, $distributor_id, $filters = []) {
        return $this->model->query()->where('pos_terminal_id', '=', $pos_id)
            ->where('distributor_id', '=', $distributor_id)
            ->paginate(PAGINATION_COUNT_ADMIN);
    }

    public function getPosBalanceTransactions($pos_terminal_id, $distributor_id, $filters = [])
    {
        $query = $this->model->query()->where('pos_terminal_id', '=', $pos_terminal_id)
            ->where('distributor_id', '=', $distributor_id);
        $this->applyTransactionsFilter($filters, $query);
        $transactions_ids = BalanceRequest::query()->whereNotNull('transaction_id')
            ->where('pos_terminal_id', '=', $pos_terminal_id)
            ->select('id', 'transaction_id')
            ->pluck('transaction_id')
            ->toArray();
        if (!empty($transactions_ids)) {
            $query = $query->whereIn('id', $transactions_ids);
        }

        return $query->paginate(PAGINATION_COUNT_ADMIN);
    }


    public function getDistributorTransactions($distributor_id, TransactionReflectionEnum $reflection)
    {
        return $this->model->query()->where('distributor_id', '=', $distributor_id)->whereNull('pos_terminal_id')->paginate(PAGINATION_COUNT_ADMIN);
    }

    public function saveBalanceRequestTransaction(BalanceRequest $balance_request, TransactionStatusEnum $status = null)
    {
        $pos_terminal = PosTerminal::query()->where('id', '=', $balance_request->pos_terminal_id)->first();

        $transaction = $this->model;
        $transaction->distributor_id = $balance_request->distributor_id;
        $transaction->pos_terminal_id = $balance_request->pos_terminal_id;
        $transaction->transaction_id = Str::uuid();
        $transaction->transaction_code = UniqueCodeGeneratorHelper::generateDigits(12, PosTerminalTransaction::class, "transaction_code");
        $transaction->amount = $balance_request->amount;
        $transaction->type = TransactionTypeEnum::CREDIT;
        $transaction->balance_before = $pos_terminal->balance;
        $transaction->balance_after = $this->getBalanceAfter($pos_terminal, $balance_request->amount, TransactionTypeEnum::CREDIT);
        $transaction->status = isset($status->value) ?  $status->value   : TransactionStatusEnum::SUCCESS->value;
        $transaction->description = isset($status->value) ? "Balance Request with mada created" : "Balance request approved and balance added";
        $transaction->created_by = $this->getCurrentUser()?->id;
        $transaction->created_by_type = $this->getCreatedByType();
        $transaction->transaction_date = now();
        $transaction->track_id = UniqueCodeGeneratorHelper::generateTrackingID();
        $transaction->save();

        return $transaction;
    }

    public function updateBalanceRequestWithMadaTransaction(BalanceRequest $balance_request, TransactionStatusEnum $status, $payment_object)
    {

    }


    public function getBalanceAfter($posTerminal, float $amount, TransactionTypeEnum $type)
    {
        if ($type == TransactionTypeEnum::CREDIT) {
            return (float)$posTerminal->balance + $amount;
        } else {
            return (float)$posTerminal->balance - $amount;
        }
    }

    public function getCreatedByType(): TransactionCreatedByTypeEnum
    {
        if (auth('adminApi')->check()) {
            return TransactionCreatedByTypeEnum::ADMIN;
        } elseif (auth('posApi')->check()) {
            return TransactionCreatedByTypeEnum::POS;
        }
        return TransactionCreatedByTypeEnum::ADMIN;
    }
    /**
     * Currency Model
     *
     * @return string
     */
    public function model(): string
    {
        return PosTerminalTransaction::class;
    }

    /**
     * @param $filters
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return void
     */
    private function applyTransactionsFilter($filters, \Illuminate\Database\Eloquent\Builder $query): void
    {
        if (!empty($filters)) {
            if (!empty($filters['amount'])) {
                $query->where('amount', '=', $filters['amount']);
            }

            if (!empty($filters['description'])) {
                $query->where('description', 'LIKE', '%' . $filters['description'] . '%');
            }

            if (!empty($filters['type'])) {
                $query->where('type', '=', $filters['type']);
            }
        }
    }

    public function updateDistributorBalance($distributor, UpdateBalanceDto $dto)
    {
        $data = $dto->toArray();

        $data['distributor_id'] = $distributor->id;
        $data['transaction_id'] = Str::uuid();
        $data['transaction_code'] = UniqueCodeGeneratorHelper::generateDigits(12, PosTerminalTransaction::class, "transaction_code");
        $data['balance_before'] = (float) $distributor->balance;
        $data['balance_after'] = $this->getBalanceAfter($distributor, $data['amount'],  $this->getTransactionType($data['type']));
        $data = $this->setTransactionData($data);

        return $this->create($data);
    }

    public function updatePosTerminalBalance($pos_terminal, array|UpdateBalanceDto $dto)
    {
        if (! is_array($dto)) {
            $data = $dto->toArray();
        } else {
            $data = $dto;
        }
        $data['distributor_id'] = $pos_terminal->distributor_id;
        $data['pos_terminal_id'] = $pos_terminal->pos_terminal_id;
        $data['balance_before'] = (float) $pos_terminal->balance;
        $data['balance_after'] = $this->getBalanceAfter($pos_terminal, $data['amount'],  $this->getTransactionType($data['type']));
        $data = $this->setTransactionData($data);
        return $this->create($data);
    }

    private function getTransactionType($type): TransactionTypeEnum
    {
        return $type == 'credit' || $type == TransactionTypeEnum::CREDIT ? TransactionTypeEnum::CREDIT : TransactionTypeEnum::DEBIT;
    }

    private function setTransactionData($data)
    {
        $data['transaction_id'] = Str::uuid();
        $data['transaction_code'] =UniqueCodeGeneratorHelper::generateDigits(12, PosTerminalTransaction::class, "transaction_code");
        $data['status'] = TransactionStatusEnum::SUCCESS;
        $data['created_by'] = $this->getCurrentUser()?->id;
        $data['created_by_type'] = $this->getCreatedByType();
        $data['transaction_date'] = now();
        $data['track_id'] = UniqueCodeGeneratorHelper::generateTrackingID();
        return $data;
    }

    private function getCurrentUser()
    {
        if (auth('adminApi')->check()) {
            return auth('adminApi')->user();
        } elseif (auth('posApi')->check()) {
            return auth('posApi')->user();
        } elseif (auth('salesRepApi')->check()) {
            return auth('salesRepApi')->user();
        }
        else {
            return null;
        }
    }
}
