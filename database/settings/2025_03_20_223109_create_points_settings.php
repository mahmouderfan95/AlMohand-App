<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('points_commission.apply_on_selling_by_mada', true);
        $this->migrator->add('points_commission.apply_on_recharging_by_mada', true);
        $this->migrator->add('points_commission.amount_per_points_redeem', 1);
        $this->migrator->add('points_commission.points_per_amount_redeem', 100);
        $this->migrator->add('points_commission.amount_per_points', 1);
        $this->migrator->add('points_commission.points_per_amount', 100);
        $this->migrator->add('points_commission.mada_fees', 0.01);
        $this->migrator->add('points_commission.mada_added_tax', 0.0017);
    }
};
