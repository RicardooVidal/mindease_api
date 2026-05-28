<?php

namespace Database\Factories;

use App\Domains\Contract\Entities\Contract;
use App\Domains\Patient\Entities\Patient;
use Illuminate\Database\Eloquent\Factories\Factory;

class ContractFactory extends Factory
{
    protected $model = Contract::class;

    public function definition(): array
    {
        return [
            'description' => $this->faker->sentence(),
            'valid_until' => now()->addYear(),
            'document' => $this->faker->filePath(),
        ];
    }

    public function withPatient(?Patient $patient = null): self
    {
        return $this->for($patient ?? Patient::factory(), 'patient');
    }
}
