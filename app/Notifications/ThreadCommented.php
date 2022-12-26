<?php

namespace App\Notifications;

use App\Models\ThreadComment;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class ThreadCommented extends Notification {
    public ThreadComment $comment;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(ThreadComment $comment) {
        $this->comment = $comment;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable) {
        return [
            // 'mail',
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
                    ->line($this->comment->author()->name . "has commented on a thread you opened in " . $this->comment->thread->project->name . ".")
                    ->action('View the thread', route('project.thread', ['project' => $this->comment->thread->project, 'thread' => $this->comment->thread]))
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
            'comment' => $this->comment
        ];
    }
}