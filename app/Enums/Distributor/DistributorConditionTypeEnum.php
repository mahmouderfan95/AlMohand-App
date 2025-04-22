<?php

namespace App\Enums\Distributor;

enum DistributorConditionTypeEnum
{
    const ORDERS_COUNT = 'orders_count';
    const ORDERS_VALUE = 'orders_value';
    const ZONE = 'zone';
    const ACCOUNT_CREATED_FROM = 'account_created_from';

    public static function getList(): array
    {
        return [
            self::ORDERS_COUNT,
            self::ORDERS_VALUE,
            self::ZONE,
            self::ACCOUNT_CREATED_FROM,
        ];
    }

    public static function getTranslatedList(): array
    {
        $trans_key = 'admin.distributor_group_condition_type';
        return [
            [
                'title' => __($trans_key . '.' . self::ORDERS_COUNT),
                'value' => self::ORDERS_COUNT,
            ],
            [
                'title' => __($trans_key . '.' . self::ORDERS_VALUE),
                'value' => self::ORDERS_VALUE,
            ],
            [
                'title' => __($trans_key . '.' . self::ZONE),
                'value' => self::ZONE,
            ],
            [
                'title' => __($trans_key . '.' . self::ACCOUNT_CREATED_FROM),
                'value' => self::ACCOUNT_CREATED_FROM,
            ],
        ];
    }
}
