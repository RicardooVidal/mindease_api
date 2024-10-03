<?php

namespace App\Domains\Payment\Enums;

enum PaymentTypeEnum: string
{
    case DINHEIRO = 'DINHEIRO';
    case CARTAO_CREDITO = 'CARTAO_CREDITO';
    case CARTAO_DEBITO = 'CARTAO_DEBITO';
    case PIX = 'PIX';
    case TRANSFERENCIA = 'TRANSFERENCIA';
    case OUTROS = 'OUTROS';
    case PENDING = 'PENDING';
}
