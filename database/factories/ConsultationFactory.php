<?php

namespace Database\Factories;

use App\Domains\Consultation\Entities\Consultation;
use App\Domains\Consultation\Enums\ConsultationTimeEnum;
use App\Domains\Consultation\Enums\ConsultationTypeEnum;
use App\Domains\Patient\Entities\Patient;
use Illuminate\Database\Eloquent\Factories\Factory;

class ConsultationFactory extends Factory
{
    protected $model = Consultation::class;

    public function definition(): array
    {
        return [
            'date' => now(),
            'time' => ConsultationTimeEnum::FIFTY_MINUTES,
            'type' => ConsultationTypeEnum::WEEKLY,
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
