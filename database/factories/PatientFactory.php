<?php

namespace Database\Factories;

use App\Domains\Patient\Entities\Patient;
use Illuminate\Database\Eloquent\Factories\Factory;

class PatientFactory extends Factory
{
    protected $model = Patient::class;

    public function definition(): array
    {
        return [
            'first_name' => $this->faker->name,
            'last_name' => $this->faker->lastName,
            'document' => $this->faker->cpf(false),
            'active' => true,
            'notes' => $this->faker->text
        ];
    }
}
