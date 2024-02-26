<?php
namespace App\Events;

use App\Models\Comment;
use App\Models\User;
use Illuminate\Queue\SerializesModels;

class CommentPosted
{
    use SerializesModels;

    public $comment;
    public $user;

    public function __construct(Comment $comment, User $user)
    {
        $this->comment = $comment;
        $this->user = $user;
    }
}