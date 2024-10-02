<?php

namespace App\Domains\Patient\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Patient extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'first_name',
        'last_name',
        'document',
        'active',
        'notes',
    ];
}
