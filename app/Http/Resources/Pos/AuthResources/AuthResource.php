<?php

namespace App\Http\Resources\Pos\AuthResources;

use App\Http\Resources\Pos\Distributor\DistributorResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AuthResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'pos_name' => $this->posTerminal?->name ?? '',
            'balance' => $this->balance ?? '',
            'points' => $this->points ?? '',
            "commission" => $this->commission ?? 0.00,
            'merchant' => new DistributorResource($this->distributor),
            'force_update' => false,
            'city' => $this->distributor->city?->name,
            'address' => $this->address,
            'phone' => $this->receiver_phone,
            'activated_at' => $this->activated_at ? date('d/m/Y', strtotime($this->activated_at)) : null,
        ];
    }
}
