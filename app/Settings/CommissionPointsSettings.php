<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class CommissionPointsSettings extends Settings
{

    public string $apply_on_selling_by_mada;
    public string $apply_on_recharging_by_mada;
    public string $amount_per_points_redeem;
    public string $points_per_amount_redeem;
    public string $amount_per_points;
    public string $points_per_amount;
    public string $mada_fees;
    public string $mada_added_tax;

    public static function group(): string
    {
        return 'points_commission';
    }
}
