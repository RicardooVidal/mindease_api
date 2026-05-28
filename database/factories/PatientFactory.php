<?php

namespace Database\Factories;

use App\Domains\Contract\Entities\Contract;
use App\Domains\Patient\Entities\Patient;
use App\Enums\GenderEnum;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class PatientFactory extends Factory
{
    protected $model = Patient::class;

    public function definition(): array
    {
        $phone = Str::replace('(', '', $this->faker->phoneNumber());
        $phone = Str::replace(')', '', $phone);
        $phone = Str::replace('-', '', $phone);

        return [
            'name' => $this->faker->name,
            'document' => $this->faker->cpf(false),
            'phone' => $phone,
            'email' => $this->faker->email,
            'gender' => $this->faker->randomElement(GenderEnum::cases()),
            'active' => true,
            'notes' => $this->faker->text
        ];
    }
}
