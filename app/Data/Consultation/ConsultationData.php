<?php

namespace App\Data\Consultation;

use App\Data\Casts\CarbonCast;
use App\Data\Patient\PatientData;
use App\Domains\Consultation\Entities\Consultation;
use App\Domains\Consultation\Enums\ConsultationTimeEnum;
use App\Domains\Consultation\Enums\ConsultationTypeEnum;
use App\Domains\Patient\Entities\Patient;
use App\Enums\PresenceEnum;
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
        public ?PresenceEnum $presence,
        public ?float $value,
        public ?string $notes,
    )
    {}

    public static function fromModel(Consultation $consultation): self
    {
        return new self(
            uuid: $consultation->uuid,
            patient: $consultation->relationLoaded('patient')
                ? PatientData::from($consultation->patient->only(['id', 'uuid', 'name', 'time', 'type',]))
                : Optional::create(),
            date: $consultation->date,
            presence: $consultation->presence,
            value: $consultation->value,
            notes: $consultation->notes,
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
                'after_or_equal:today'
            ],
            'presence' => [
                'required',
                Rule::enum(PresenceEnum::class)
            ],
            'value' => [
                'nullable',
                'decimal:2',
                'min:0'
            ],
            'notes' => [
                'nullable',
                'string',
                'max:500',
            ]
        ];
    }

    public static function prepareForPipeline(array $properties): array
    {
        if (!empty($properties['value']) && !is_string($properties['value'])) {
            $properties['value'] = number_format($properties['value'], 2);
        }

        return $properties;
    }

    public static function messages(...$args): array
    {
        return [
            'patient.required' => 'O paciente é obrigatório.',
            'date.after_or_equal' => 'A data da consulta deve ser hoje ou uma data futura.',
            'presence.required' => 'O tipo de presença é obrigatório.',
            'value.min' => 'O valor deve ser no mínimo 0'
        ];
    }
}
