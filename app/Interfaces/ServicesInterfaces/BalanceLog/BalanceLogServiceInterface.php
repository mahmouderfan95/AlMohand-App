<?php

namespace App\Interfaces\ServicesInterfaces\BalanceLog;

use App\Interfaces\ServicesInterfaces\BaseServiceInterface;

interface BalanceLogServiceInterface extends BaseServiceInterface
{
    public function redeem($pos_terminal_id, $type, $amount);

    public function getPointsCashbackValue($points);

    public function getPosPointsTransactions($pos_terminal_id);

    public function getPointsTransactionsByDistributorPosTerminal($distributor_pos_terminal_id);

    public function getCommissionTransactionsByDistributorPosTerminal($distributor_pos_terminal_id);
}
