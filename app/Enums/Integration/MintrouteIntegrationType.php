<?php



namespace App\Enums\Integration;


use App\Enums\Order\OrderProductType;

enum MintrouteIntegrationType:string {

    case MINROUTE = 'mintroute';
    case MINROUTE_VOUCHER = 'mintroute_voucher';
    // case MINROUTE_TOPUP = 'mintroute_topup';

    public static function resolve(string $name, string $type): string
    {
        if ($name != self::MINROUTE->value) {
            return $name;
        }
        return match ($type) {
            // OrderProductType::getTypeTopUp() => self::MINROUTE_TOPUP->value,
            OrderProductType::getTypeSerial() => self::MINROUTE_VOUCHER->value,
            default => $name,
        };
    }

}
