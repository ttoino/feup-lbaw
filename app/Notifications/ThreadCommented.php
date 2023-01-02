<?php

namespace App\Notifications;

use App\Models\ThreadComment;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class ThreadCommented extends Notification {
    public ThreadComment $thread_comment;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(ThreadComment $comment) {
        $this->thread_comment = $comment;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable) {
        return [
            CustomDatabaseChannel::class
        ];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable) {
        return (new MailMessage)
                    ->line($this->thread_comment->author()->name . "has commented on a thread you opened in " . $this->thread_comment->thread->project->name . ".")
                    ->action('View the thread', route('project.thread', ['project' => $this->thread_comment->thread->project, 'thread' => $this->thread_comment->thread]))
                    ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable) {
        return [
            'thread_comment' => $this->thread_comment
        ];
    }
}