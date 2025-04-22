<?php

namespace App\Enums\PosTerminalTransaction;

enum TransactionReflectionEnum:string
{
    case BALANCE = 'balance';
    case COMMISSION = 'commission';
    case SALES = 'sales';
}
