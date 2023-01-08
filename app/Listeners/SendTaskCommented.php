<?php

namespace App\Listeners;

use App\Events\TaskCommentEvent;
use App\Notifications\TaskCommented;

class SendTaskCommented {

    public function handle(TaskCommentEvent $event) {
        foreach($event->comment->task->assignees as $assignee){
            $assignee->notify(new TaskCommented($event->comment));
        }
        
    }
}