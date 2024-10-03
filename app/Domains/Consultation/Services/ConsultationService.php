<?php

namespace App\Domains\Consultation\Services;

use App\Domains\Consultation\Entities\Consultation;
use App\Domains\Consultation\Repositories\ConsultationRepository;
use App\Domains\Consultation\DTO\Requests\ConsultationParamsDTO;

class ConsultationService
{
    public function __construct(
        private readonly ConsultationRepository $consultationRepository
    ) {}

    public function getAll(array $filters = []): array
    {
        return $this->consultationRepository->getAll($filters)->toArray();
    }

    public function create(ConsultationParamsDTO $paramsDTO): array
    {
        return $this->consultationRepository
            ->create($paramsDTO->toArray())
            ->toArray();
    }

    public function updateByModel(ConsultationParamsDTO $paramsDTO, Consultation $consultation): bool
    {
        return $this->consultationRepository
            ->updateByModel($consultation, $paramsDTO->toArray());
    }

    public function deleteByModel(Consultation $consultation): void
    {
        $this->consultationRepository->deleteByModel($consultation);
    }
}
