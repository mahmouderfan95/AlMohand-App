<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Resources\Json\JsonResource;

class ZoneResource extends BaseAdminResource
{
    public function toArray($request)
    {
        return [
            "id" => $this->id,
            "name" => $this->name,
            "city_id" => $this->city_id,
            //"translations" => $this->formatTranslations($this->translations, 'zone_id'),
        ];
    }
}
