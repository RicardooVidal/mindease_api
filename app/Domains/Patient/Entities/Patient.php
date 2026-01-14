<?php

namespace App\Domains\Patient\Entities;

use App\Models\Traits\HasUuid;
use Database\Factories\PatientFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

/**
 * @property-read int $id
 * @property string $uuid
 * @property string $first_name
 * @property string $last_name
 * @property string $document
 * @property bool $active
 * @property string|null $notes
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 *
 * @method static PatientFactory factory($count = null, $state = [])
 */
class Patient extends Model
{
    use HasFactory, HasUuid, SoftDeletes;
    protected $fillable = [
        'first_name',
        'last_name',
        'document',
        'active',
        'notes',
    ];

    public static function newFactory(): PatientFactory
    {
        return PatientFactory::new();
    }
}
