<?php

namespace App\Domains\Patient\Repositories;

use App\Domains\Patient\Entities\Patient;
use App\Domains\Patient\Entities\WaitList;

class WaitListRepository
{
    public function __construct(
        private readonly WaitList $waitList
    ) {}

    public function create(int $patientId): WaitList
    {
        return $this->waitList->create([
            'patient_id' => $patientId
        ]);
    }

    public function delete(int $patientId): void
    {
        $this->waitList->wherePatientId($patientId)->delete();
    }
}
