<?php

namespace App\Interfaces\ServicesInterfaces\PosTerminal;

use App\Interfaces\ServicesInterfaces\BaseServiceInterface;
use Illuminate\Http\Request;

interface PosTerminalServiceInterface extends BaseServiceInterface
{
    public function generateName();

    public function getAllActiveTerminals($keyword);
}
