<?php

namespace App\Events;

use App\Models\ThreadComment;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\SerializesModels;

abstract class ThreadCommentEvent {
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public ThreadComment $comment;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(ThreadComment $comment) {
        $this->comment = $comment;
    }
}