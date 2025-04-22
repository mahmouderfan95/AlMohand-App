<?php

namespace App\Traits;

use App\Enums\GeneralStatusEnum;

trait ActiveQueryScope
{
    /**
     * @param $query
     * @return mixed
     */
    public function scopeActive($query): mixed
    {
        return $query->where('status', GeneralStatusEnum::getStatusActive());
    }
}
