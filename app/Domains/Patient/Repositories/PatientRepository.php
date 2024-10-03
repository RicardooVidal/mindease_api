<?php

namespace App\Domains\Patient\Repositories;

use App\Domains\Patient\Entities\Patient;
use Illuminate\Support\Collection;

class PatientRepository
{
    public function __construct(
        private readonly Patient $patient
    ) {}

    public function getAll(array $filters, bool $paginated = true): Collection
    {
        return $this->patient
            ->when(!empty($filters), fn($query) => $query->where($filters))
            ->when($paginated, fn($query) => $query->paginate(10), fn($query) => $query->get())
            ->map(fn($patient) => [
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
        return $this->patient->findOrFail($id);
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
