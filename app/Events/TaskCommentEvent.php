<?php

namespace App\Events;

use App\Models\TaskComment;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\SerializesModels;

abstract class TaskCommentEvent {
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public TaskComment $comment;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(TaskComment $comment) {
        $this->comment = $comment;
    }
}