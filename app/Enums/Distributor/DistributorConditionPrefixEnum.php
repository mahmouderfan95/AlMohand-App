<?php

namespace App\Enums\Distributor;

enum DistributorConditionPrefixEnum
{
    const GREATER_THAN = 'greater_than';
    const LOWER_THAN = 'lower_than';
    const BETWEEN = 'between';

    public static function getList()
    {
        return [
            self::GREATER_THAN,
            self::LOWER_THAN,
            self::BETWEEN
        ];
    }

    public static function getTranslatedList(): array
    {
        $trans_key = 'admin.distributor_group_prefix';
        return [
            [
                "title" => __($trans_key . '.' . self::GREATER_THAN),
                "value" => self::GREATER_THAN
            ],
            [
                "title" =>  __($trans_key . '.' . self::LOWER_THAN),
                "value" => self::LOWER_THAN
            ],
            [
                "title" => __($trans_key . '.' . self::BETWEEN),
                "value" => self::BETWEEN
            ]
        ];
    }
}
