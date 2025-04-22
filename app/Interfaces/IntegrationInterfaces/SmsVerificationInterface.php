<?php

namespace App\Interfaces\IntegrationInterfaces;

interface SmsVerificationInterface
{
    public function sendFourDigitOtp($phone);
    public function sendSixDigitOtp($phone);

}
