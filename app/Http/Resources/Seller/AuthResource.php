<?php

namespace App\Http\Resources\Seller;

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
            'name' => $this->name ?? '',
            'owner_name' => $this->owner_name ?? '',
            'email' => $this->email ?? '',
            'approval_status' => $this->approval_status,
            'status' => $this->status,
            'country' => $this->sellerAddress?->country?->name ??'',
            'city' => $this->sellerAddress?->city?->name ??'',
            'address' => $this->address_details ??'',
            'created_at' => $this->created_at->format('Y-m-d') ?? '',
            'attachments' => SellerAttachmentResource::collection($this->sellerAttachment) ?? [],
        ];
    }
}
