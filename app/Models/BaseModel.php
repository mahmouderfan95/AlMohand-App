<?php

namespace App\Models;

use App\Traits\ActiveQueryScope;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class BaseModel extends Model
{
    use ActiveQueryScope;

    public function getCreatedAtAttribute($value): string
    {
        return Carbon::parse($value)->setTimezone(config('app.timezone'))->setTimezone(config('app.timezone'))
            ->format('Y-m-d H:i:s');
    }

    public function getUpdatedAtAttribute($value): string
    {
        return Carbon::parse($value)->setTimezone(config('app.timezone'))->setTimezone(config('app.timezone'))
            ->format('Y-m-d H:i:s');
    }

    /*public function getDeletedAtAttribute($value): string
    {
        return Carbon::parse($value)->setTimezone(config('app.timezone'))->setTimezone(config('app.timezone'))
            ->format('Y-m-d H:i:s');
    }*/
}
