<?php

namespace App\Domains\Contract\Entities;

use App\Domains\Patient\Entities\Patient;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Contract extends Model
{
    protected $fillable = [
        'patient_id',
        'description',
        'valid_until',
        'document'
    ];

    // public function getDocumentAttribute($value)
    // {
    //     if (is_resource($value)) {
    //         return stream_get_contents($value);
    //     }

    //     return $value;
    // }

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }
}
