<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class CompanyFactory extends Factory
{
    public function definition(): array
    {
        return [
            'company' => $this->faker->company(),
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'document' => $this->faker->cnpj(false),
            'until' => $this->faker->dateTimeBetween('+1 month', '+1 year')->format('Y-m-d'),
            'active' => true,
        ];
    }
}
