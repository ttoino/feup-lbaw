<?php

namespace App\Listeners;

use App\Events\ProjectEvent;
use App\Notifications\ProjectDeleted as ProjectDeletedNotif;

class SendProjectDeleted {

    public function handle(ProjectEvent $event) {
        foreach($event->project->users as $user){
            $user->notify(new ProjectDeletedNotif($event->project));
        }
    }
}