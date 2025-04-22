<?php

namespace App\Http\Controllers\Admin\Report;

use App\DTO\Admin\PosTerminal\CreatePosTerminalDto;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\PosTerminalRequests\PosTerminalRequest;
use App\Interfaces\ServicesInterfaces\PosTerminal\PosTerminalServiceInterface;
use OpenApi\Annotations as OA;

class OrdersReportController extends Controller
{
    /**
     * Attribute  Constructor.
     */
    public function __construct()
    {
    }


    public function index()
    {
    }
}
