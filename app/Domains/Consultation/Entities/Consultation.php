<?php

namespace App\Domains\Consultation\Entities;

use App\Domains\Patient\Entities\Patient;
use App\Events\ConsultationCreatedEvent;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Consultation extends Model
{
    protected $fillable = [
        'patient_id',
        'date',
        'time'
    ];

    protected $dispatchesEvents = [
        'created' => ConsultationCreatedEvent::class,
    ];

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }
}
