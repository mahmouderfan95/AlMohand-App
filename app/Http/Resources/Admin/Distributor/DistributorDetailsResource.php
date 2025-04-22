<?php

namespace App\Http\Resources\Admin\Distributor;

use App\Http\Resources\Admin\BaseAdminResource;
use App\Http\Resources\Admin\CityResource;
use App\Http\Resources\Admin\SalesRepUserResource;
use App\Http\Resources\Admin\ZoneResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DistributorDetailsResource extends BaseAdminResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id,
            "distributor_group" => [
                'id' => $this->distributor_group_id ?? null,
                'name' => $this->group->name ?? null
            ],
            "sales_rep" => [
                'id' => $this->sales_rep_id,
                'name' => $this->sales_rep->name ?? null
            ],
            "name" => $this->name,
            "code" => $this->code,
            "logo" => $this->logo,
            "manager_name" => $this->manager_name,
            "balance" => $this->balance,
            "commission_balance" => $this->commission_balance,
            'total_pos_balance' => $this->posTerminals->sum('balance'),
            "total_pos_commission" => $this->posTerminals->sum('commission'),
            "owner_name" => $this->owner_name,
            "phone" => $this->phone,
            "email" => $this->email ?? null,
            "address" => $this->address ?? null,
            "commercial_register" => $this->commercial_register,
            "tax_card" => $this->tax_card,
            "pos_terminals_count" => $this->posTerminals?->count(),
            "is_active" => $this->is_active ?? false,
            "translations" => $this->formatTranslations($this->translations, 'distributor_id'),
            "attachments" => $this->when($this->attachments, DistributorAttachmentResource::collection($this->whenLoaded('attachments'))),
            "city" => $this->when($this->city, new CityResource($this->whenLoaded('city'))),
            "zone" => $this->when($this->zone, new ZoneResource($this->whenLoaded('zone'))),
            "region" => $this->when($this->region, new ZoneResource($this->whenLoaded('region'))),
            "created_at" => $this->created_at ?? null,
            "updated_at" => $this->updated_at ?? null,
        ];
    }
}
