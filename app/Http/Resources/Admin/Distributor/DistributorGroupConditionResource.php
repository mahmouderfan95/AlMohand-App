<?php

namespace App\Http\Resources\Admin\Distributor;

use App\Http\Resources\Admin\BrandImageResource;
use App\Http\Resources\Admin\BrandTranslationResource;
use Illuminate\Http\Resources\Json\JsonResource;

class DistributorGroupConditionResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'condition_type' => $this->condition_type,
            'prefix' => $this->prefix,
            'value' => $this->value,
        ];
    }
}
