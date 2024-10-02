<?php

namespace App\Domains\Patient\Repositories;

use App\Domains\Patient\Entities\Patient;
use mysql_xdevapi\Collection;

class PatientRepository
{
    public function __construct(
        private readonly Patient $patient
    ) {}

    public function getAll(array $filters): Collection
    {
        return $this->patient->all()?->map(fn($patient) => [
            'id' => $patient->id,
            'first_name' => $patient->first_name,
            'last_name' => $patient->last_name,
            'document' => $patient->document,
            'active' => $patient->active,
            'notes' => $patient->notes
        ]);
    }

    public function getById(int $id): ?Patient
    {
        return $this->patient->find($id);
    }

    public function create(array $params): Patient
    {
        return $this->patient->create($params);
    }

    public function update(array $params): bool
    {
        return $this->patient->update($params);
    }

    public function delete(int $id): void
    {
        $patient = $this->patient->find($id);

        if ($patient)
        {
            $patient->delete();
        }
    }

    public function updateByModel(Patient $patient, array $params): bool
    {
        return $patient->update($params);
    }

    public function deleteByModel(Patient $patient): void
    {
        $patient->delete();
    }
}
