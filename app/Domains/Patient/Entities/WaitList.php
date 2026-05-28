<?php

namespace App\Domains\Patient\Entities;

use App\Models\Traits\HasUuid;
use Carbon\Carbon;
use Database\Factories\PatientFactory;
use Database\Factories\WaitListFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property-read int $id
 * @property string $uuid
 * @property int $patient_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 *
 * @method static WaitListFactory factory($count = null, $state = [])
 */
class WaitList extends Model
{
    use HasFactory, HasUuid, SoftDeletes;

    protected $table = 'wait_list';
    protected $fillable = [
        'patient_id'
    ];

    public static function newFactory(): WaitListFactory
    {
        return WaitListFactory::new();
    }
}
