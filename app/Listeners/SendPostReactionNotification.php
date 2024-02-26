<?php

namespace App\Listeners;

use App\Events\PostReacted;
use App\Models\Notification;

class SendPostReactionNotification
{
    public function handle(PostReacted $event)
    {
        Notification::create([
            'user_id' => $event->post->user_id, // El usuario que recibirá la notificación
            'type' => 'reaction', // Tipo de notificación
            'related_id' => $event->user->id, // El usuario que hizo la reacción
            'read' => false, // Inicialmente no leída
            'notification_date' => now(), // Fecha actual
        ]);
    }
}
