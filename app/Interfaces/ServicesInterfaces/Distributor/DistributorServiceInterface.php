<?php

namespace App\Interfaces\ServicesInterfaces\Distributor;

use App\DTO\Admin\Merchant\GetDistributorPosDto;
use App\Interfaces\ServicesInterfaces\BaseServiceInterface;
use Illuminate\Http\Request;

interface DistributorServiceInterface extends BaseServiceInterface
{
    public function uploadDistributorAttachments(Request $request, $id);

    public function deleteAttachment($id);

    public function fillRequiredData();

    public function updateStatus($id, bool $is_active);
}
