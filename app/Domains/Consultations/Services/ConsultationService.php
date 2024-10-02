<?php

namespace App\Domains\Consultations\Services;

use App\Domains\Consultations\Repositories\ConsultationRepository;

class ConsultationService
{
    public function __construct(
        private readonly ConsultationRepository $consultationRepository
    ) {}
}
