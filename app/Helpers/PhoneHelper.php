<?php

namespace App\Helpers;

use Propaganistas\LaravelPhone\PhoneNumber;

class PhoneHelper
{
    public static function getFormattedPhone($phone): array|string
    {
        $formatted_phone = (new PhoneNumber('+'. $phone))->formatE164();
        return str_replace('+', '', $formatted_phone);
    }
}
