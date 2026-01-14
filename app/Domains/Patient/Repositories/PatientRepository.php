<?php

namespace App\Domains\Patient\Repositories;

use App\Data\Patient\IndexPatientData;
use App\Data\Patient\PatientData;
use App\Domains\Patient\Entities\Patient;
use App\Helpers\DocumentHelper;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use MongoDB\BSON\Document;

class PatientRepository
{
    public function __construct(
        private readonly Patient $patient
    ) {}

    public function getAll(IndexPatientData $filters, bool $paginated = true): Collection|LengthAwarePaginator
    {
        /** @var Collection|LengthAwarePaginator */
        return $this->patient
            ->query()
            ->when(
                $filters->uuid, fn($query) => $query->where('uuid', $filters->uuid)
            )
            ->when(
                $filters->document, fn($query) =>
                $query->where('document', DocumentHelper::removeMask($filters->document))
            )
            ->when(
                $filters->firstName, fn($query) => $query->where('first_name', 'like', "%{$filters->firstName}%")
            )
            ->when($paginated, fn($query) => $query->paginate(10), fn($query) => $query->get());
    }

    public function getByUuid(string $uuid): ?PatientData
    {
        return PatientData::from($this->patient->where('uuid', $uuid)->firstOrFail()->toArray());
    }

    public function create(PatientData $patientData): Patient
    {
        return $this->patient->create($patientData->except('uuid')->toArray());
    }

    public function update(PatientData $patientData): bool
    {
        return $this->patient
            ->where('uuid', $patientData->uuid)
            ->update($patientData->except('uuid')->toArray());
    }

    public function delete(string $uuid): void
    {
        $patient = $this->patient->where('uuid', $uuid)->firstOrFail();
        $patient->delete();
    }
}
