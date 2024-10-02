<?php

namespace App\Consultations\Entities;

use Illuminate\Database\Eloquent\Model;

class Consultation extends Model
{
    protected $fillable = [
        'patient_id',
        'date'
    ];
}
