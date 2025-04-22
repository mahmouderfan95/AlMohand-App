<?php

namespace App\Enums\PosTerminalTransaction;

enum TransactionCreatedByTypeEnum:string
{
    case ADMIN = 'admin';
    case SALES_REP = 'sales_rep';
    case MERCHANT = 'merchant';
    case POS = 'pos';
    case USER = 'user';
}
