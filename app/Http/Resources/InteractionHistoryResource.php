<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InteractionHistoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'post_id' => $this->post_id,
            'interaction_type' => $this->interaction_type,
            'interaction_date' => $this->interaction_date,
            'post' => PostResource::make($this->whenLoaded('post')),
        ];
    }
}
