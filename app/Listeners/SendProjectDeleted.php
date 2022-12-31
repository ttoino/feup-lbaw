<?php

namespace App\Listeners;

use App\Events\ProjectDeleted;
use App\Events\ProjectEvent;
use App\Models\User;
use App\Notifications\ProjectDeleted as ProjectDeletedNotif;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Log;

class SendProjectDeleted {

    public function handle(ProjectEvent $event) {
        foreach($event->project->users as $user){
            $user->notify(new ProjectDeletedNotif($event->project));
        }
        
    }
}