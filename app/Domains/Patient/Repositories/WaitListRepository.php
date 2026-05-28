<?php

namespace App\Domains\Patient\Repositories;

use App\Data\Patient\PatientData;
use App\Data\WaitList\WaitListData;
use App\Domains\Patient\Entities\Patient;
use App\Domains\Patient\Entities\WaitList;

class WaitListRepository
{
    public function __construct(
        private readonly WaitList $waitList
    ) {}

    public function create(WaitListData $waitListData): WaitList
    {
        return $this->waitList->create([
            'patient_id' => Patient::query()
                ->where('uuid', $waitListData->patientUuid)
                ->firstOrFail()
                ->id,
        ]);
    }

    public function delete(string $uuid): void
    {
        $this->waitList->where('uuid', $uuid)->firstOrFail()->delete();
    }
}
