<?php

namespace App\Listeners;

use App\Events\CommentPosted;
use App\Models\Notification;

class SendCommentPostedNotification
{
    public function handle(CommentPosted $event)
    {
        Notification::create([
            'user_id' => $event->comment->post->user_id, // Suponiendo que cada comentario está asociado a un post.
            'type' => 'comment',
            'related_id' => $event->user->id,
            'read' => false,
            'notification_date' => now(),
        ]);
    }
}