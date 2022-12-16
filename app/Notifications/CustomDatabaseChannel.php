<?php

namespace App\Notifications;

use App\Models\Notification as NotificationModel;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class CustomDatabaseChannel {

    public function send($notifiable, Notification $notification) {
        $model = $notification->toDatabase($notifiable);

        return (new NotificationModel($model))->save();
    }

}