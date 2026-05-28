<?php

namespace App\Data\Consultation;

use App\Data\Casts\CarbonCast;
use App\Domains\Consultation\Enums\ConsultationTimeEnum;
use App\Domains\Consultation\Enums\ConsultationTypeEnum;
use Carbon\Carbon;
use Illuminate\Validation\Rule;
use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Attributes\MapOutputName;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;

#[MapName(SnakeCaseMapper::class)]
class IndexConsultationData extends Data
{
    public function __construct(
        public ?string $uuid = null,
        public ?string $patientUuid = null,
        #[WithCast(CarbonCast::class)]
        public ?Carbon $date = null,
        public ?ConsultationTypeEnum $type = null,
        public ?ConsultationTimeEnum $time = null,
    ) {}

    public static function rules(): array
    {
        return [
            'uuid' => [
                'nullable',
                'uuid',
            ],
            'patient_uuid' => [
                'nullable',
                'uuid',
            ],
            'date' => [
                'nullable',
                'date',
            ],
            'type' => [
                'nullable',
                Rule::enum(ConsultationTypeEnum::class)
            ],
            'time' => [
                'nullable',
                Rule::enum(ConsultationTimeEnum::class)
            ],
        ];
    }
}
