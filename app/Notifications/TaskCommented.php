<?php

namespace App\Notifications;

use App\Models\TaskComment;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class TaskCommented extends Notification {
    public TaskComment $comment;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(TaskComment $comment) {
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
                    ->line($comment->author()->name . "has left a comment on a task you're assigned to - " . $comment->task()->name . ".")
                    ->action('View the task', route('project.task.info', ['project' => $comment->task()->project, 'task' => $comment->task]))
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