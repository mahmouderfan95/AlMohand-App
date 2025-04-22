<?php

namespace App\Helpers;

use App\Models\POSTerminal\PosTerminal;
use Exception;
use Illuminate\Support\Str;

class UniqueOtpGeneratorHelper
{
    /**
     * @throws Exception
     */
    public static function generate($length, $model_class, $column): string
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

}
