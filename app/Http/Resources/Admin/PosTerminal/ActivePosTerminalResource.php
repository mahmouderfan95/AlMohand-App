<?php

namespace App\Http\Resources\Admin\PosTerminal;

use App\Http\Resources\Admin\BaseAdminResource;
use App\Http\Resources\Admin\CityResource;
use App\Http\Resources\Admin\ZoneResource;
use App\Http\Resources\Pos\Distributor\DistributorResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ActivePosTerminalResource extends BaseAdminResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->posTerminal->id,
            'name' => $this->posTerminal->name,
            'merchant' => new DistributorResource($this->distributor),
            'status' => (bool)$this->is_active,
            'balance' => $this->balance,
            'created_at' => $this->created_at,
        ];
    }
}
