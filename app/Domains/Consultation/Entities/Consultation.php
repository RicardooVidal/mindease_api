<?php

namespace App\Domains\Consultation\Entities;

use Illuminate\Database\Eloquent\Model;

class Consultation extends Model
{
    protected $fillable = [
        'patient_id',
        'date',
        'time'
    ];
}
