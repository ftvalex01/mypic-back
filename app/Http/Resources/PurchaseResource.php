<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PurchaseResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'shop_item_id' => $this->shop_item_id,
            'purchase_date' => $this->purchase_date,
            'shopItem' => ShopItemResource::make($this->whenLoaded('shopItem')),
        ];
    }
}
