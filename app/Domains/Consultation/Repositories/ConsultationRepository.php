<?php

namespace App\Domains\Consultation\Repositories;

use App\Data\Consultation\ConsultationData;
use App\Data\Consultation\IndexConsultationData;
use App\Domains\Consultation\Entities\Consultation;
use App\Domains\Consultation\Entities\Contract;
use App\Domains\Patient\Entities\Patient;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use staabm\SideEffectsDetector\SideEffect;

class ConsultationRepository
{
    public function __construct(
        private readonly Consultation $consultation
    ) {}

    public function getByUuid(string $uuid): Consultation
    {
        return $this->consultation
            ->with(['patient:id,uuid,name,time,type'])
            ->where('uuid', $uuid)
            ->firstOrFail();
    }

    public function getAll(IndexConsultationData $filters): Collection
    {
        return $this->consultation
            ->with(['patient:id,uuid,name,document'])
            ->when($filters->uuid, fn($query, $uuid) => $query->where('uuid', $uuid))
            ->when(
                $filters->date,
                fn($query, $date) => $query->whereBetween('date', [
                    $date->startOfDay()->toDateTimeString(),
                    $date->endOfDay()->toDateTimeString(),
                ]))
            ->when($filters->type, fn($query, $type) => $query->where('type', $type->value))
            ->when($filters->time, fn($query, $time) => $query->where('time', $time->value))
            ->when(
                $filters->patientUuid,
                fn(Builder $query, $patientUuid) =>
                $query->whereHas('patient', fn ($q) => $q->where('uuid', $patientUuid))
            )
            ->get()
            ->makeHidden('patient_id');
    }

    public function create(ConsultationData $consultationData): Consultation
    {
        $consultation = $this->consultation->make($consultationData->toArray());

        $patient = Patient::query()
            ->where('uuid', $consultationData->patient->uuid)
            ->firstOrFail();

        $consultation->patient()->associate($patient);
        $consultation->save();

        return $consultation;
    }

    public function update(ConsultationData $consultationData): bool
    {
        return $this->consultation
            ->where('uuid', $consultationData->uuid)
            ->update($consultationData->except('consultation','patient')->toArray());
    }

    public function delete(string $uuid): void
    {
        $consultation = $this->consultation->where('uuid', $uuid)->firstOrFail();

        $consultation->delete();
    }
}
