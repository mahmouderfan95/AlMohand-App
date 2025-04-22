<?php

namespace App\Models\POSTerminal;

use App\Models\BaseModel;
use App\Traits\UuidDefaultValue;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property string $id
 * @property string $distributor_pos_terminal_id
 * @property string $ip
 * @property string $location
*/
class PosTerminalLogins extends BaseModel
{
    /**
     * @var string
     */
    protected $table = 'pos_terminal_logins';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'distributor_pos_terminal_id',
        'app_version',
        'ip',
        'location',
    ];
}
