<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class NotificationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'type' => $this->type,
            'related_id' => $this->related_id,
            'read' => $this->read,
            'notification_date' => $this->notification_date,
            'user' => UserResource::make($this->whenLoaded('user')),
        ];
    }
}
