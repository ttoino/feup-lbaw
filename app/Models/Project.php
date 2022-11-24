<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model {
    const CREATED_AT = 'creation_date';
    const UPDATED_AT = 'last_modification_date';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'archived',
        'description',
        'coordinator'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'fts_search'
    ];

    public function coordinator() {
        return $this->belongsTo(User::class, 'coordinator');
    }

    public function users() {
        return $this->belongsToMany(
            User::class,
            'project_member',
            'project',
            'user_profile'
        );
    }

    public function taskGroups() {
        return $this->hasMany(
            TaskGroup::class,
            'project'
        )->orderBy('position');
    }

    public function tasks() {
        return $this->hasManyThrough(
            Task::class,
            TaskGroup::class,
            'project',
            'task_group'
        );
    }

    public function reports() {
        return $this->hasMany(Report::class, 'project');
    }

    protected $table = 'project';
}