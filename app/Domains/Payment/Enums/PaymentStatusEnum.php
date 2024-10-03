<?php

namespace App\Domains\Payment\Enums;

enum PaymentStatusEnum: string
{
    case PENDING = 'PENDING';
    case NOT_PAID = 'NOT_PAID';
    case CANCELLED = 'CANCELLED';
}
