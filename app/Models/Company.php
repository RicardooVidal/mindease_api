<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\HasUuid;

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
 */
class Company extends Model
{
    use HasFactory, HasUuid;

    protected $fillable = [
        'company', 'name', 'email', 'document', 'contract', 'until', 'active'
    ];

    protected $table = 'companies';
}
