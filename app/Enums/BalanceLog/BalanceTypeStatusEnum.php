<?php

namespace App\Enums\BalanceLog;

enum BalanceTypeStatusEnum: string
{
    case POINTS = 'points';
    case COMMISSION = 'commission';

    public static function getList(): array
    {
        return [
            self::POINTS->value,
            self::COMMISSION->value
        ];
    }
}
