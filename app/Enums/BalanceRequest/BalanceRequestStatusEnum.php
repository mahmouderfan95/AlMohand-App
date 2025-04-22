<?php

namespace App\Enums\BalanceRequest;

enum BalanceRequestStatusEnum
{
    const PENDING = 'pending';
    const ACCEPTED = 'accepted';
    const REJECTED = 'rejected';

    public static function getList(): array
    {
        return [
            self::PENDING,
            self::ACCEPTED,
            self::REJECTED
        ];
    }
}
