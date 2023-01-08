<?php

namespace App\Listeners;

use App\Events\ThreadEvent;
use App\Notifications\ThreadNew;

class SendThreadNew {

    public function handle(ThreadEvent $event) {
        foreach($event->thread->project->users as $user){
            $user->notify(new ThreadNew($event->thread));
        }
        
    }
}