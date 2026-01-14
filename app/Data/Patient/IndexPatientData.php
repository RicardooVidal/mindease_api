<?php

namespace App\Data\Patient;

use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;

#[MapName(SnakeCaseMapper::class)]
class IndexPatientData extends Data
{
    public function __construct(
        public ?string $uuid = null,
        public ?string $firstName = null,
        public ?string $document = null,
    ) {}

    public static function rules(): array
    {
        return [
            'uuid' => [
                'nullable',
                'uuid',
            ],
            'first_name' => [
                'nullable',
                'string',
                'min:3',
                'max:255',
            ],
            'document' => [
                'nullable',
                'string',
                'max:11',
            ],
        ];
    }
}
