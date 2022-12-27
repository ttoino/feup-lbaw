<?php

namespace App\Notifications;

use App\Models\Task;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class TaskAssigned extends Notification {
    public Task $task;
    public User $assigner;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Task $task, User $assigner) {
        $this->task = $task;
        $this->assigner = $assigner;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable) {
        return [
            'mail',
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
                    ->line("You've been assigned to a task in " . $this->task->project()->name . " by " . $this->assigner->name .  ".")
                    ->action('View the task', route('project.task.info', ['project' => $this->task->project, 'task' => $this->task]))
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
            'task' => $this->task->id,
            'assigner' => $this->assigner->id,
        ];
    }
}