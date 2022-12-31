<?php

namespace App\Providers;

use App\Events\UserCreated;
use App\Events\UserDeleted;
use App\Events\UserUpdated;
use App\Events\ProjectDeleted;
use App\Events\TaskCommentCreated;
use App\Events\ThreadCreated;
use App\Events\ThreadCommentCreated;
use App\Listeners\CreateDefaultProfilePic;
use App\Listeners\SendProjectDeleted;
use App\Listeners\SendTaskCommented;
use App\Listeners\SendThreadNew;
use App\Listeners\SendThreadCommented;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider {
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],

        UserCreated::class => [
            CreateDefaultProfilePic::class
        ],
        UserUpdated::class => [
            CreateDefaultProfilePic::class
        ],
        UserDeleted::class => [],
        ProjectDeleted::class => [
            SendProjectDeleted::class
        ],
        TaskCommentCreated::class => [
            SendTaskCommented::class
        ],
        ThreadCreated::class => [
            SendThreadNew::class
        ],
        ThreadCommentCreated::class => [
            SendThreadCommented::class
        ]
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot() {
        //
    }
}