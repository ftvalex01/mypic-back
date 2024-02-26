<?php
namespace App\Listeners;

use App\Events\UserFollowed;
use App\Models\Notification;
use App\Models\User;

class SendUserFollowedNotification
{
    public function handle(UserFollowed $event)
    {
        // Determina si el usuario al que se quiere seguir tiene el perfil privado
        if ($event->followed->is_private) {
            $type = 'follow_request';
        } else {
            $type = 'follow';
        }

        Notification::create([
            'user_id' => $event->followed->id,
            'type' => $type, // Aquí usamos la variable $type que puede ser 'follow' o 'follow_request'
            'related_id' => $event->follower->id,
            'read' => false,
            'notification_date' => now(),
        ]);
    }
}
