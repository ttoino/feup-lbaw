<?php

namespace App\Events;

use App\Models\Thread;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\SerializesModels;

abstract class ThreadEvent {
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Thread $thread;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Thread $thread) {
        $this->thread = $thread;
    }
}