<?php

namespace App\Helpers;

use App\Models\POSTerminal\PosTerminal;
use Exception;
use Illuminate\Support\Str;

class UniqueCodeGeneratorHelper
{
    /**
     * @throws Exception
     */
    public static function generateDigits($length, $model_class, $column): string
    {
        do {
            $characters = '0123456789';
            $charactersLength = strlen($characters);
            $otp = '';
            for ($i = 0; $i < $length; $i++) {
                $otp .= $characters[rand(0, $charactersLength - 1)];
            }
            $isUnique = !$model_class::query()->where($column, $otp)->exists();
        } while (!$isUnique);

        return $otp;
    }

    public static function generateDigitsWithDate($length, $model_class, $column): string
    {
        do {
            $characters = '0123456789';
            $charactersLength = strlen($characters);
            $otp = '';
            for ($i = 0; $i < $length; $i++) {
                $otp .= $characters[rand(0, $charactersLength - 1)];
            }
            $otp = $prefix = date('y') . date('m') . date('d') . $otp;
            $isUnique = !$model_class::query()->where($column, $otp)->exists();
        } while (!$isUnique);

        return $otp;
    }

    public static function generateTrackingID(): string
    {
        return date('y-m-d') . '-' . rand(1000000, 9999999);
    }

    public function generateWithDateAndDigits()
    {

    }

}
