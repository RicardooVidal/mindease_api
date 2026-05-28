<?php

namespace App\Domains\Contract\Repositories;

use App\Data\Contract\ContractData;
use App\Data\Contract\IndexContractData;
use App\Domains\Contract\Entities\Contract;
use App\Domains\Patient\Entities\Patient;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class ContractRepository
{
    public function __construct(
        private readonly Contract $contract
    ) {}

    public function getAll(IndexContractData $filters): Collection
    {
        return $this->contract
            ->with(['patient:id,uuid,name'])
            ->when($filters->uuid, fn($query, $uuid) => $query->where('uuid', $uuid))
            ->when(
                $filters->validUntil,
                fn(Builder$query, Carbon $validUntil) => $query->whereBetween('valid_until', [
                    $validUntil->startOfDay()->toDateTimeString(),
                    $validUntil->endOfDay()->toDateTimeString(),
                ]))
            ->when(
                $filters->description,
                fn(Builder $query, string $description) =>
                    $query->where('description', 'like', "%$description%")
                )
            ->when(
                $filters->patientUuid,
                fn(Builder $query, string $patientUuid) =>
                    $query->whereHas('patient', fn ($q) => $q->where('uuid', $patientUuid))
            )
            ->get()
            ->makeHidden(['document', 'patient_id']);
    }

    public function getByUuid(string $uuid): ?Contract
    {
        return $this->contract
            ->with(['patient:id,uuid,name'])
            ->where('uuid', $uuid)
            ->firstOrFail();
    }


    public function create(ContractData $contractData, string $document): Contract
    {
        $contract = $this->contract->make([...$contractData->toArray(), 'document' => $document]);

        $patient = Patient::query()
            ->where('uuid', $contractData->patient->uuid)
            ->firstOrFail();

        $contract->patient()->associate($patient);
        $contract->save();

        return $contract;
    }

    public function update(ContractData $contractData, Patient $patient): bool
    {
        $contract = $this->contract->where('uuid', $contractData->uuid)->firstOrFail();
        $contract->patient()->associate($patient);

        return $contract->update($contractData->toArray());
    }

    public function delete(string $uuid): void
    {
        Contract::query()->where('uuid', $uuid)->firstOrFail()->delete();
    }
}
