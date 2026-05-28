<?php

namespace App\Domains\Consultation\Entities;

use App\Domains\Consultation\Enums\ConsultationTimeEnum;
use App\Domains\Consultation\Enums\ConsultationTypeEnum;
use App\Domains\Patient\Entities\Patient;
use App\Events\ConsultationCreatedEvent;
use App\Models\Traits\HasUuid;
use Carbon\Carbon;
use Database\Factories\ConsultationFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property-read int $id
 * @property-read string $uuid
 * @property int $patient_id
 * @property Carbon $date
 * @property ConsultationTimeEnum $time
 * @property ConsultationTypeEnum $type
 * @property int $duration
 *
 * @property-read Patient $patient
 *
 * @method static ConsultationFactory factory($count = null, $state = [])
 */
class Consultation extends Model
{
    use HasFactory, HasUuid, SoftDeletes;

    protected $hidden = ['id'];

    protected $table = 'consultations';

    protected $fillable = [
        'date',
        'type',
        'time',
    ];

    protected function casts(): array
    {
        return [
            'date' => 'datetime',
            'time' => ConsultationTimeEnum::class,
            'type' => ConsultationTypeEnum::class,
        ];
    }

    protected $dispatchesEvents = [
        'created' => ConsultationCreatedEvent::class,
    ];

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public static function newFactory(): ConsultationFactory
    {
        return ConsultationFactory::new();
    }
}
