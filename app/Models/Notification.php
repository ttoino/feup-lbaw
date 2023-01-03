<?php

namespace App\Models;

use App\Casts\Datetime;
use App\Casts\NotificationJson;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model {
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'json',
        'type',
        'notifiable_id',
        'read_date',
        'creation_date'
    ];

    protected $casts = [
        'creation_date' => Datetime::class,
        'read_date' => Datetime::class,
        'json' => NotificationJson::class,
    ];

    /**
     * Mark the notification as read.
     *
     * @return void
     */
    public function markAsRead() {
        if (is_null($this->read_date)) {
            $this->forceFill(['read_date' => $this->freshTimestamp()])->save();
        }
    }

    /**
     * Mark the notification as unread.
     *
     * @return void
     */
    public function markAsUnread() {
        if (!is_null($this->read_date)) {
            $this->forceFill(['read_date' => null])->save();
        }
    }

    /**
     * Determine if a notification has been read.
     *
     * @return bool
     */
    public function read() {
        return $this->read_date !== null;
    }

    /**
     * Determine if a notification has not been read.
     *
     * @return bool
     */
    public function unread() {
        return $this->read_date === null;
    }

    /**
     * Scope a query to only include read notifications.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeRead(Builder $query) {
        return $query->whereNotNull('read_date');
    }

    /**
     * Scope a query to only include unread notifications.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeUnread(Builder $query) {
        return $query->whereNull('read_date');
    }

    protected $table = 'notification';
}