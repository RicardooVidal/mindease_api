<?php

namespace App\Data;

use App\Models\Company;
use Illuminate\Validation\Rule;
use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;

#[MapName(SnakeCaseMapper::class)]
class RegisterUserData extends Data
{
    public function __construct(
        public string $name,
        public string $email,
        public int $companyId,
        public string $password,
        public string $passwordConfirmation
    ) {}

    public static function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'companyUuid' => [
                'required',
                Rule::exists(Company::class, 'uuid')->withoutTrashed()
            ],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ];
    }
}
