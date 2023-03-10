<?php

namespace App\Notifications;

use App\Models\Project;
use Illuminate\Notifications\Messages\MailMessage;

class ProjectInvite extends Notification {
    public string $url;
    public Project $project;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(string $url, Project $project) {
        $this->url = $url;
        $this->project = $project;
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
                    ->line("You've been invited to join " . $this->project->name . ".")
                    ->action('Join this project', url($this->url))
                    ->line('If you think this was not intended for you or if you do not have interest in joining this project, please ignore this message.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable) {
        return [
            'url' => $this->url,
            'project' => $this->project
        ];
    }
}
