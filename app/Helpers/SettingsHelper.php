<?php

namespace App\Helpers;

use App\Settings\CommissionPointsSettings;

class SettingsHelper
{
    public static function getPointsCommissionSetting($key, $default = null)
    {
        $setting = app(CommissionPointsSettings::class);
        return $setting->$key ?? $default;
    }
}
