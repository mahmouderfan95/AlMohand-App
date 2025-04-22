<?php



namespace App\Enums\Order;


enum OrderPaymentMethod:string {

    case MADA = 'mada';
    case BALANCE = 'balance';

    public static function getList(): array
    {
        return [
            self::getMada(),
            self::getBalance(),
        ];
    }
    public static function getMada(): string
    {
        return self::MADA->value;
    }
    public static function getBalance(): string
    {
        return self::BALANCE->value;
    }

}
