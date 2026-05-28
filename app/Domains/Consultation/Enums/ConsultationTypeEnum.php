<?php

namespace App\Domains\Consultation\Enums;

use App\Attributes\Description;
use App\Traits\WithDescription;

enum ConsultationTypeEnum: string
{
    use WithDescription;

    #[Description('Diário')]
    case DAILY = 'daily';

    #[Description('Semanal')]
    case WEEKLY = 'weekly';

    #[Description('Mensal')]
    case MONTHLY = 'monthly';
}
