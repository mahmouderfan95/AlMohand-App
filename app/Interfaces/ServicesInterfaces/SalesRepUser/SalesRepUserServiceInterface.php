<?php

namespace App\Interfaces\ServicesInterfaces\SalesRepUser;

use App\Interfaces\ServicesInterfaces\BaseServiceInterface;
use Illuminate\Http\Request;

interface SalesRepUserServiceInterface extends BaseServiceInterface
{
    public function update_status(Request $request, int $id);

    public function merchants($id);

    public function posTerminalByMerchantID($distributor_id);

    public function allMerchantsWithPosTerminalByMerchantID();

    public function addTransaction(\App\Http\Requests\Admin\SalesRepUserRequests\AddTransactionRequest $request, int $id);

    public function permissions(Request $request);

    public function transactions(Request $request);

    public function allBalanceRequests(Request $request);


}
