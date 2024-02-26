<?php

namespace App\Events;

use App\Models\Post;
use App\Models\User;
use Blueprint\Contracts\Model;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PostReacted
{
    use Dispatchable, SerializesModels;

    public $reactable;
    public $post;
    public $user;
    public $reactionType;
    /**
     * Create a new event instance.
     *
     * @param  Model  $reactable
     * @param  User  $user
     * @param  string  $reactionType
     * @return void
     */
    public function __construct(Model $reactable, User $user, string $reactionType)
    {
        $this->reactable = $reactable;
        $this->user = $user;
        $this->reactionType = $reactionType;
    }
}
