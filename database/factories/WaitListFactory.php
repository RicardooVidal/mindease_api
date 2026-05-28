<?php

namespace Database\Factories;

use App\Domains\Patient\Entities\Patient;
use App\Domains\Patient\Entities\WaitList;
use Illuminate\Database\Eloquent\Factories\Factory;

class WaitListFactory extends Factory
{
    protected $model = WaitList::class;

    public function definition(): array
    {
        return [

        ];
    }

    public function withPatient(?Patient $patient = null): self
    {
        return $this->state(function () use ($patient) {
            return [
                'patient_id' => $patient?->id ?? Patient::factory(),
            ];
        });
    }
}
