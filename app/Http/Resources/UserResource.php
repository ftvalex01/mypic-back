<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'username' => $this->username,
            'email' => $this->email,
            'birth_date' => $this->birth_date,
            'register_date' => $this->register_date,
            'bio' => $this->bio,
            'email_verified_at' => $this->email_verified_at,
            'available_pines' => $this->available_pines,
            'profile_picture' => $this->profile_picture ? asset('storage/'.$this->profile_picture) : null,
            'accumulated_points' => $this->accumulated_points,
            'purchases' => PurchaseCollection::make($this->whenLoaded('purchases')),
        ];
        
    }
    
}
