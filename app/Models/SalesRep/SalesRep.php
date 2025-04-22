<?php

namespace App\Models\SalesRep;

use App\Models\BalanceLog\BalanceLog;
use App\Models\Distributor\Distributor;
use App\Models\Role;
use App\Models\SalesRepLevel\SalesRepLevel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\PermissionRegistrar;
use Tymon\JWTAuth\Contracts\JWTSubject;

class SalesRep extends Authenticatable implements JWTSubject
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'parent_id',
        'city_id',
        'sales_rep_level_id',
        'name',
        'username',
        'email',
        'phone',
        'password',
        'code',
        'balance',
        'status',
    ];

    /**
     * Get the parent user.
     */
    public function parent()
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    /**
     * Get the child users.
     */
    public function children()
    {
        return $this->hasMany(self::class, 'parent_id');
    }
    /**
     * Get the child users.
     */
    public function sales_rep_locations(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(SalesRepLocation::class);
    }

    /**
     * Get the child users.
     */
    public function transactions(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(SalesRepTransaction::class);
    }


    /**
     * Get the user level associated with the user.
     */
    public function sales_rep_level(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(SalesRepLevel::class, 'sales_rep_level_id');
    }
    /**
     * Get the user level associated with the user.
     */
    public function distributors(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Distributor::class);
    }


    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims(): array
    {
        return [];
    }
    public function roles(): BelongsToMany
    {
        $relation = $this->morphToMany(
            Role::class,
            'model',
            config('permission.table_names.model_has_roles'),
            config('permission.column_names.model_morph_key'),
            app(PermissionRegistrar::class)->pivotRole
        );

        if (! app(PermissionRegistrar::class)->teams) {
            return $relation;
        }

        $teamField = config('permission.table_names.roles').'.'.app(PermissionRegistrar::class)->teamsKey;

        return $relation->wherePivot(app(PermissionRegistrar::class)->teamsKey, getPermissionsTeamId())
            ->where(fn ($q) => $q->whereNull($teamField)->orWhere($teamField, getPermissionsTeamId()));
    }
    protected function getDefaultGuardName(): string { return 'salesRepApi'; }
}
