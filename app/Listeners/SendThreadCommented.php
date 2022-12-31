<?php

namespace App\Listeners;

use App\Events\ThreadCommentCreated;
use App\Events\ThreadCommentEvent;
use App\Notifications\ThreadCommented;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Log;

class SendThreadCommented {

    public function handle(ThreadCommentEvent $event) {
        $event->comment->thread->author->notify(new ThreadCommented($event->comment));  
    }
}