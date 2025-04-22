<?php

namespace App\Interfaces\ServicesInterfaces\SalesRepLevel;

use App\Interfaces\ServicesInterfaces\BaseServiceInterface;
use Illuminate\Http\Request;

interface SalesRepLevelServiceInterface extends BaseServiceInterface
{
    public function update_status(Request $request, int $id);
}
