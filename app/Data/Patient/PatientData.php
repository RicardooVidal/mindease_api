<?php

namespace App\Data\Patient;

use Illuminate\Validation\Rule;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Support\Validation\ValidationContext;

class PatientData extends Data
{
    public function __construct(
        public ?string $uuid = null,
        public ?string $first_name = null,
        public ?string $last_name = null,
        public ?string $document = null,
        public ?bool $active = null,
        public ?string $notes = null,
    )
    {
    }

    public static function rules(ValidationContext $context): array
    {
        return [
            'first_name' => ['required', 'string', 'min:2', 'max:100'],
            'last_name' => ['required', 'string', 'min:2', 'max:100'],
            'document' => [
                'required',
                'string',
                'min:11',
                'max:14',
                Rule::unique('patients', 'document')->withoutTrashed()
            ],
            'active' => ['required', 'boolean'],
            'notes' => ['nullable', 'string', 'max:500']
        ];
    }
}
