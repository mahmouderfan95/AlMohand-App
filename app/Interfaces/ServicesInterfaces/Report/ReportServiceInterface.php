<?php

namespace App\Interfaces\ServicesInterfaces\Report;

use Illuminate\Http\Request;

interface ReportServiceInterface
{
    public function orderReports(Request $request, $distributor_pos_terminal): mixed;

    public function balanceReports(Request $request, $distributor_pos_terminal);

    public function commissionReports(Request $request, $user);

    public function pointReports(Request $request,  $user);

    public function balanceRequestReports(Request $request, $user);

}
