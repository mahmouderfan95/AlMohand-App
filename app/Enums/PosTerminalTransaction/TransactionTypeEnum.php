<?php

namespace App\Enums\PosTerminalTransaction;

enum TransactionTypeEnum:string
{
    case CREDIT = 'credit';
    case DEBIT = 'debit';
}
