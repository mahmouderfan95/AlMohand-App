<?php

namespace App\Http\Resources\Admin\PosTerminal;

use App\Http\Resources\Admin\BaseAdminResource;
use App\Http\Resources\Admin\CityResource;
use App\Http\Resources\Admin\ZoneResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PosTerminalResource extends BaseAdminResource
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
            'name' => $this->name,
            'brand' => $this->brand,
            'serial_number' => $this->serial_number,
            'terminal_id' => $this->terminal_id,
            'status' => $this->status,
            'balance' => $this->balance,
            'created_at' => $this->created_at,
        ];
    }
}
