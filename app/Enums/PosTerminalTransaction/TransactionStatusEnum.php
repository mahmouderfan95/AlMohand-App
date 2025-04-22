<?php

namespace App\Enums\PosTerminalTransaction;

enum TransactionStatusEnum:string
{
    case DRAFT = 'draft';
    case SUCCESS = 'success';
    case PENDING = 'pending';
    case FAILED = 'failed';
    case REVERSED = 'reversed';
    case CANCELLED = 'cancelled';
}
