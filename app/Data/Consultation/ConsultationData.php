<?php

namespace App\Data\Consultation;

use App\Data\Casts\CarbonCast;
use App\Data\Patient\PatientData;
use App\Domains\Consultation\Entities\Consultation;
use App\Domains\Consultation\Enums\ConsultationTimeEnum;
use App\Domains\Consultation\Enums\ConsultationTypeEnum;
use App\Domains\Patient\Entities\Patient;
use Carbon\Carbon;
use Illuminate\Validation\Rule;
use Spatie\LaravelData\Attributes\FromRouteParameterProperty;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Optional;
use Spatie\LaravelData\Support\Validation\ValidationContext;

class ConsultationData extends Data
{
    public function __construct(
        #[FromRouteParameterProperty('consultation')]
        public ?string $uuid = null,
        public PatientData $patient,
        #[WithCast(CarbonCast::class)]
        public ?Carbon $date = null,
        public ConsultationTimeEnum $time,
        public ConsultationTypeEnum $type,
    )
    {}

    public static function fromModel(Consultation $consultation): self
    {
        return new self(
            uuid: $consultation->uuid,
            patient: $consultation->relationLoaded('patient')
                ? PatientData::from($consultation->patient->only(['id', 'uuid', 'first_name', 'last_name']))
                : Optional::create(),
            date: $consultation->date,
            time: $consultation->time,
            type: $consultation->type,
        );
    }

    public static function rules(ValidationContext $context): array
    {
        return [
            'patient' => [
                'required',
            ],
            'date' => [
                'required',
                'date',
                'date_format:Y-m-d',
                'after_or_equal:today'
            ],
            'time' => [
                'required',
                'int',
                Rule::enum(ConsultationTimeEnum::class)
            ],
            'type' => [
                'required',
                'string',
                Rule::enum(ConsultationTypeEnum::class),
            ]
        ];
    }
}
