<?php

namespace App\Domains\Consultations\Repositories;

use App\Consultations\Entities\Consultation;

class ConsultationRepository
{
    public function __construct(
        private readonly Consultation $consultation
    ) {}
}
