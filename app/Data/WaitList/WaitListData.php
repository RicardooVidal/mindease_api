<?php

namespace App\Data\WaitList;

use Illuminate\Validation\Rule;
use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;
use Spatie\LaravelData\Support\Validation\ValidationContext;

#[MapName(SnakeCaseMapper::class)]
class WaitListData extends Data
{
    public function __construct(
        public ?string $uuid = null,
        public string $patientUuid
    ) {}

    public static function rules(ValidationContext $context): array
    {
        return [
            'patient_uuid' => [
                'required',
                'uuid',
            ]
        ];
    }
}
