<?php

namespace App\Http\Resources\SalesRep\BalanceLog;

use App\Helpers\SettingsHelper;
use App\Http\Resources\Admin\BaseAdminResource;
use Illuminate\Http\Request;

class PosCommissionsTransactionsResource extends BaseAdminResource
{
    public function toArray(Request $request): array
    {
        $commission = $this->balance_after - $this->balance_before;

        $mada_fees = SettingsHelper::getPointsCommissionSetting('mada_fees');
        $mada_added_tax = SettingsHelper::getPointsCommissionSetting('mada_added_tax');

        $total_percentage = ($mada_fees + $mada_added_tax) / 100;
        $net_profit = $commission / (1 + $total_percentage);

        return [
            "id" => $this->id,
            "order_id" => $this->transaction?->order_id,
            "net_profit" => round($net_profit, 2),
            "bank_commission" => $commission -  round($net_profit, 2),
            "created_at" => $this->created_at,
            "transaction_type" => $this->transaction_type,
            "commission" => $commission,
            "transaction_id" => $this?->transaction_id ?? null
        ];
    }
}
