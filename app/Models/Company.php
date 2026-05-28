<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Carbon\Carbon;
use Database\Factories\CompanyFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\HasUuid;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property-read string $id
 * @property-read string $uuid
 * @property string $company
 * @property string $name
 * @property string $email
 * @property int $document
 * @property string|null $contract
 * @property Carbon $until
 * @property bool $active
 *
 * @method static CompanyFactory factory($count = null, $state = [])
 */
class Company extends Model
{
    use HasFactory, HasUuid;

    protected $fillable = [
        'company', 'name', 'email', 'document', 'contract', 'until', 'active'
    ];

    protected $table = 'companies';

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public static function newFactory(): CompanyFactory
    {
        return CompanyFactory::new();
    }
}
