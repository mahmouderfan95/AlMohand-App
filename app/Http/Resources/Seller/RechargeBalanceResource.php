<?php

namespace App\Http\Resources\Seller;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RechargeBalanceResource extends JsonResource
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
            'recharge_balance_type' => $this->recharge_balance_type,
            'bank_name' => $this->bank_name,
            'transferring_name' => $this->transferring_name,
            'amount' => $this->amount,
            'receipt_image' => $this->ReceiptImageUrl,
            'type' => $this->type,
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
        ];
    }
}
