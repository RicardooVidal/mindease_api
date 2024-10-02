<?php

namespace App\Domains\Patient\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class WaitList extends Model
{
    protected $table = 'wait_list';
    protected $fillable = [
        'patient_id'
    ];
}
