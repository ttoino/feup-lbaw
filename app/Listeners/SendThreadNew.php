<?php

namespace App\Listeners;

use App\Events\ThreadCreated;
use App\Events\ThreadEvent;
use App\Notifications\ThreadNew;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Log;

class SendThreadNew {

    public function handle(ThreadEvent $event) {
        foreach($event->thread->project->users as $user){
            $user->notify(new ThreadNew($event->thread));
        }
        
    }
}