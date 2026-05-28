<?php

namespace App\Domains\Consultation\Services;

use App\Data\Consultation\ConsultationData;
use App\Data\Consultation\IndexConsultationData;
use App\Domains\Consultation\DTO\Requests\ConsultationParamsDTO;
use App\Domains\Consultation\Entities\Consultation;
use App\Domains\Consultation\Repositories\ConsultationRepository;
use App\Domains\Patient\Entities\Patient;

class ConsultationService
{
    public function __construct(
        private readonly ConsultationRepository $consultationRepository
    ) {}

    public function getAll(IndexConsultationData $filters): array
    {
        return $this->consultationRepository->getAll($filters)->toArray();
    }

    public function getByUuid(string $uuid): ConsultationData
    {
        return ConsultationData::from($this->consultationRepository->getByUuid($uuid));
    }

    public function create(ConsultationData $consultationData, Patient $patient): ConsultationData
    {
        $consultation = $this->consultationRepository->create($consultationData);
        $consultation->patient()->associate($patient);
        $consultation->save();

        return ConsultationData::from($consultation);
    }

    public function update(ConsultationData $consultationData, Patient $patient): ConsultationData
    {
        $this->consultationRepository->update($consultationData);
        $consultation = Consultation::query()->where('uuid', $consultationData->uuid)->firstOrFail();
        $consultation->patient()->associate($patient);
        $consultation->save();

        return ConsultationData::from($consultation);
    }

    public function delete(string $uuid): void
    {
        $this->consultationRepository->delete($uuid);
    }
}
