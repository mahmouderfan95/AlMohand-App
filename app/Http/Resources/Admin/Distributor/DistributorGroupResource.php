<?php

namespace App\Http\Resources\Admin\Distributor;

use App\Http\Resources\Admin\BaseAdminResource;

class DistributorGroupResource extends BaseAdminResource
{
    /**
     * @param $request
     * @return array
     */
    public function toArray($request): array
    {
        return [
            "id" => $this->id,
            "name" => $this->name,
            "description" => $this->description,
            "is_active" => $this->is_active ?? false,
            "is_auto_assign" => $this->is_auto_assign ?? false,
            "is_require_all_conditions" => $this->is_require_all_conditions ?? false,
            "created_at" => $this->created_at ?? false,
            "updated_at" => $this->updated_at ?? false,
            "translations" => $this->formatTranslations($this->translations, 'distributor_group_id'),
            "conditions" => $this->conditions ? DistributorGroupConditionResource::collection($this->conditions) : null,
        ];
    }
}
