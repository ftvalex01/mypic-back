<?php

namespace App\Providers;

use App\Events\CommentPosted;
use App\Events\PostReacted;
use App\Events\UserFollowed;
use App\Listeners\SendCommentPostedNotification;
use App\Listeners\SendPostReactionNotification;
use App\Listeners\SendUserFollowedNotification;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;


class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        PostReacted::class => [
            SendPostReactionNotification::class,
        ],
        UserFollowed::class => [
            SendUserFollowedNotification::class,
        ],
        CommentPosted::class => [
            SendCommentPostedNotification::class,
        ],
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        parent::boot();
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
