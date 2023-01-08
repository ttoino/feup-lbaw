<?php

namespace App\Listeners;

use App\Events\ThreadCommentEvent;
use App\Notifications\ThreadCommented;

class SendThreadCommented {

    public function handle(ThreadCommentEvent $event) {
        $event->comment->thread->author->notify(new ThreadCommented($event->comment));  
    }
}