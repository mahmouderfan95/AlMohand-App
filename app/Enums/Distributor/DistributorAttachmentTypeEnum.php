<?php

namespace App\Enums\Distributor;

enum DistributorAttachmentTypeEnum:string
{
    case IDENTITY = 'identity_files';
    case COMMERCIAL_REGISTER = 'commercial_register_files';
    case TAX_CARD = 'tax_card_files';
    case MORE = 'more_files';

    /**
     * @return array
     */
    public static function getList(): array
    {
        return [
            self::getTypeIdentity(),
            self::getTypeCommercialRegister(),
            self::getTypeTaxCard(),
            self::getTypeMore(),
        ];
    }

    /**
     * @return string
     */
    public static function getTypeIdentity(): string
    {
        return self::IDENTITY->value;
    }

    /**
     * @return string
     */
    public static function getTypeCommercialRegister(): string
    {
        return self::COMMERCIAL_REGISTER->value;
    }

    /**
     * @return string
     */
    public static function getTypeTaxCard(): string
    {
        return self::TAX_CARD->value;
    }

    /**
     * @return string
     */
    public static function getTypeMore(): string
    {
        return self::MORE->value;
    }
}
