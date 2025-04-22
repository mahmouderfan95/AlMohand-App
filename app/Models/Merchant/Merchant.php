<?php

namespace App\Models\Merchant;

use App\Traits\UuidDefaultValue;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Str;
use Tymon\JWTAuth\Contracts\JWTSubject;

/**
 * @property string $id
 * @property string $name
 * @property string $email
 * @property string $phone
 * @property string $password
 * @property string $verified_at
 * @property boolean $is_blocked
 * @property boolean $is_owner
 * @property boolean $is_active
*/
class Merchant extends Authenticatable implements JWTSubject
{
    use SoftDeletes, UuidDefaultValue;

    /**
     * @var string
     */
    protected $table = 'merchant';

    protected $keyType = 'string';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
        'verified_at',
        'is_blocked',
        'is_owner',
        'is_active'
    ];

    /**
     * @var string[]
     */
    protected $casts = [
        'name' => 'string',
        'email' => 'string',
        'phone' => 'string',
        'password' => 'string',
        'verified_at' => 'string',
        'is_blocked' => 'boolean',
        'is_owner' => 'boolean',
        'is_active' => 'boolean',
    ];

    protected $hidden = ['password', 'deleted_at'];


    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims(): array
    {
        return [];
    }

    protected function getDefaultGuardName(): string { return 'merchantApi'; }
}
