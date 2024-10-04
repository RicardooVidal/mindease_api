<?php

namespace App\Domains\Payment\Entities;

use App\Domains\Consultation\Entities\Consultation;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    protected $fillable = [
        'consultation_id',
        'value',
        'status',
        'notes',
        'type',
    ];

    public function consultation(): BelongsTo
    {
        return $this->belongsTo(Consultation::class);
    }
}
