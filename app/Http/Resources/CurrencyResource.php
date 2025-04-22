<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CurrencyResource extends JsonResource
{
    /**
     * Category data into an array.
     *
     */
    public function toArray($request)
    {
        return [
            "value" => $this->value,
            "status" => $this->status,
            "decimal_place" => $this->decimal_place,
            "is_default" => $this->is_default,
            "name" => $this->name,
            "code" => $this->code,
        ];
    }
}
