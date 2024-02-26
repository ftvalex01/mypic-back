<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;

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
            'related_user' => new UserResource($this->whenLoaded('relatedUser')),
            'user_name' => optional($this->whenLoaded('user'))->name,
            'post_id' => $this->when($this->type === 'reaction' || $this->type === 'comment', function () {
                // Asumiendo que related_id es el ID del post para notificaciones de tipo 'reaction' y 'comment'
                return $this->related_id; // Usa directamente related_id si se refiere al post
            }),
            'time_ago' => Carbon::parse($this->notification_date)->diffForHumans(),
        ];
    }
}
