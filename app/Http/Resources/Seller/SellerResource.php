<?php

namespace App\Http\Resources\Seller;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SellerResource extends JsonResource
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
            'name'  => $this->name,
            'owner_name'  => $this->owner_name,
            'email'  => $this->email,
            'phone'  => $this->phone,
            'status' => $this->status,
            'approval_status' => $this->approval_status,
            'logo' => $this->logo,
            'balance' => $this->balance,
            'sellerAttachment' => $this->whenLoaded('sellerAttachment') ? SellerAttachmentResource::collection($this->sellerAttachment): '',
        ];
    }
}
