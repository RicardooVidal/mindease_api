<?php

namespace App\Domains\Contract\Entities;

use App\Domains\Patient\Entities\Patient;
use App\Models\Traits\HasUuid;
use Carbon\Carbon;
use Database\Factories\ContractFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

/**
 * @property-read string $uuid
 * @property-read int $id
 * @property-read int $patient_id
 * @property string $description
 * @property Carbon $valid_until
 * @property string $document
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @property-read Patient $patient
 * @property-read string|null $document_url
 * @method static ContractFactory factory($count = null, $state = [])
 */
class Contract extends Model
{
    use HasUuid, HasFactory;

    protected $hidden = ['id'];

    protected $appends = ['document_url'];

    protected $fillable = [
        'description',
        'valid_until',
        'document'
    ];

    protected $casts = [
        'valid_until' => 'datetime',
    ];

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function documentUrl(): Attribute
    {
         return Attribute::make(
            get: fn() => $this->document
                ? Storage::disk('app_files')->url("$this->document")
                : null
        );
    }

    public static function newFactory(): ContractFactory
    {
        return ContractFactory::new();
    }
}
