<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;

class Task extends Model {
    use Notifiable;
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'description',
        'edit_date',
        'state',
        'position'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'fts_search'
    ];

    public function project() {
        return $this->hasOneThrough(
            Project::class,
            TaskGroup::class,
            'id',
            'id',
            'task_group',
            'project'
        );
    }

    public function taskGroup() {
        return $this->belongsTo(
            TaskGroup::class,
            'task_group'
        );
    }

    public function creator() {
        return $this->belongsTo(User::class, 'creator');
    }

    public function comments() {
        return $this->hasMany(
            TaskComment::class,
            'task'
        );
    }

    public function tags() {
        return $this->belongsToMany(
            Tag::class,
            'task_tag',
            'task',
            'tag'
        );
    }

    public function assignees() {
        return $this->belongsToMany(
            User::class,
            'task_assignee',
            'task',
            'user_profile'
        );
    }

    protected $table = 'task';
}