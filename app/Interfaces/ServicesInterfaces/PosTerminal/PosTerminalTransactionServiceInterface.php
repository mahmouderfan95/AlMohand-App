<?php

namespace App\Interfaces\ServicesInterfaces\PosTerminal;

use App\DTO\Admin\Merchant\GetDistributorTransactionsDto;
use App\DTO\Admin\Merchant\UpdateBalanceDto;
use App\DTO\Admin\PosTerminal\GetPosTerminalTransactionsDto;
use App\Interfaces\ServicesInterfaces\BaseServiceInterface;


interface PosTerminalTransactionServiceInterface extends BaseServiceInterface
{
    /**
     * @param GetPosTerminalTransactionsDto $dto
     * @return mixed
     */
    public function getPosTransactionsList(GetPosTerminalTransactionsDto $dto): mixed;

    /**
     * @param $pos_terminal_id
     * @param GetPosTerminalTransactionsDto $dto
     * @return mixed
     */
    public function getPosBalanceTransactions($pos_terminal_id, GetPosTerminalTransactionsDto $dto): mixed;

    /**
     * @param $pos_terminal_id
     * @param GetPosTerminalTransactionsDto $dto
     * @return mixed
     */
    public function getPosSalesTransactions($pos_terminal_id, GetPosTerminalTransactionsDto $dto): mixed;

    /**
     * @param $distributor_id
     * @param GetDistributorTransactionsDto $dto
     * @return mixed
     */
    public function getDistributorBalanceTransactions($distributor_id, GetDistributorTransactionsDto $dto): mixed;
    /**
     * @param $distributor_id
     * @param GetDistributorTransactionsDto $dto
     * @return mixed
     */
    public function getDistributorCommissionTransactions($distributor_id, GetDistributorTransactionsDto $dto): mixed;

    /**
     * @param $distributor_id
     * @param UpdateBalanceDto $dto
     * @return mixed
     */
    public function updateDistributorBalance($distributor_id, UpdateBalanceDto $dto);

    /**
     * @param $distributor_pos_terminal_id
     * @param UpdateBalanceDto $dto
     * @return mixed
     */
    public function updatePosBalance($distributor_pos_terminal_id, UpdateBalanceDto $dto);
}
