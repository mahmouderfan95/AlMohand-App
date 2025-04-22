<?php

namespace App\Http\Resources\SalesRep\AuthResources;

use App\Http\Resources\Admin\Distributor\DistributorPosTerminalListResource;
use App\Http\Resources\Admin\Distributor\DistributorResource;
use App\Http\Resources\Admin\PosTerminal\PosTerminalResource;
use App\Http\Resources\Admin\SalesRepLevelResource;
use App\Http\Resources\Admin\SalesRepLocationResource;
use App\Http\Resources\Admin\SalesRepUserResource;
use App\Http\Resources\SalesRep\SalesRepTransactionResource;
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
        // Collect all distributors and their POS terminals
        $distributors = DistributorResource::collection($this->distributors);
        $posTerminals = $this->distributors->flatMap(function ($distributor) {
            return $distributor->posTerminals;
        });

        return [
            'id' => $this->id,
            'name' => $this->name,
            'username' => $this->username,
            'email' => $this->email,
            'phone' => $this->phone,
            'code' => $this->code,
            'balance' => $this->balance,
            'status' => $this->status,
            'parent_id' => $this->parent_id,
            'transactions' => SalesRepTransactionResource::collection($this->transactions),
            'distributors' => $distributors,  // All distributors
            'posTerminals' => DistributorPosTerminalListResource::collection($posTerminals),  // All POS terminals across distributors
            'parent' => new SalesRepUserResource($this->whenLoaded('parent')),
            'children' => SalesRepUserResource::collection($this->whenLoaded('children')),
            'sales_rep_level' => new SalesRepLevelResource($this->whenLoaded('sales_rep_level')),
            'sales_rep_locations' => SalesRepLocationResource::collection($this->whenLoaded('sales_rep_locations')),
            'created_at' => $this->created_at ? $this->created_at->toDateTimeString() : null,
            'updated_at' => $this->updated_at ? $this->updated_at->toDateTimeString() : null,
        ];
    }
}
