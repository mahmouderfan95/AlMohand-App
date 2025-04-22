<?php

namespace App\Interfaces\ServicesInterfaces\Distributor;

use App\DTO\Admin\Merchant\GetDistributorPosDto;
use App\Interfaces\ServicesInterfaces\BaseServiceInterface;
use Illuminate\Http\Request;

interface DistributorPosTerminalServiceInterface extends BaseServiceInterface
{
    public function getPosTerminalsList(GetDistributorPosDto $dto);
    public function updateStatus($id, bool $is_active);
    public function factoryReset($pos_terminal_id, $otp);
}
