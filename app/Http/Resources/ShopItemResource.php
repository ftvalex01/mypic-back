<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ShopItemResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'cost_in_points' => $this->cost_in_points,
            'type' => $this->type,
            'purchases' => PurchaseCollection::make($this->whenLoaded('purchases')),
        ];
    }
}
