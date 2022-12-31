<?php

namespace App\Listeners;

use App\Events\TaskCommentCreated;
use App\Events\TaskCommentEvent;
use App\Notifications\TaskCommented;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Log;

class SendTaskCommented {

    public function handle(TaskCommentEvent $event) {
        foreach($event->comment->task->assignees as $assignee){
            $assignee->notify(new TaskCommented($event->comment));
        }
        
    }
}