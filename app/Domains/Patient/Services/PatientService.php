<?php

namespace App\Domains\Patient\Services;

use App\Domains\Patient\DTO\Requests\PatientParamsDTO;
use App\Domains\Patient\Entities\Patient;
use App\Domains\Patient\Repositories\PatientRepository;

class PatientService
{
    public function __construct(
        private readonly PatientRepository $patientRepository
    ) {}

    public function getAll(array $filters = []): array
    {
        return $this->patientRepository->getAll($filters)->toArray();
    }

    public function getById(int $id): array
    {
        return $this->patientRepository->getById($id)->toArray();
    }

    public function create(PatientParamsDTO $paramsDTO): array
    {
        return $this->patientRepository
            ->create($paramsDTO->toArray())
            ->toArray();
    }

    public function updateByModel(PatientParamsDTO $paramsDTO, Patient $patient): bool
    {
        return $this->patientRepository
            ->updateByModel($patient, $paramsDTO->toArray());
    }

    public function deleteByModel(Patient $patient): void
    {
        $this->patientRepository->deleteByModel($patient);
    }
}
