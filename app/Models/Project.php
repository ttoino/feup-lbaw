<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Project extends Model {
    use HasFactory;

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
        'coordinator_id'
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
        return $this->belongsTo(User::class, 'coordinator_id');
    }

    public function users() {
        return $this->belongsToMany(
            User::class,
            'project_member',
            'project_id',
            'user_profile_id'
        )->withPivot('is_favorite');
    }

    public function taskGroups() {
        return $this->hasMany(
            TaskGroup::class,
            'project_id'
        )->orderBy('position');
    }

    public function tasks() {
        return $this->hasManyThrough(
            Task::class,
            TaskGroup::class,
            'project_id',
            'task_group_id'
        );
    }

    public function tags() {
        return $this->hasMany(
            Tag::class,
            'project_id'
        );
    }

    public function threads() {
        return $this->hasMany(
            Thread::class,
            'project_id'
        )->orderBy('creation_date');
    }

    public function reports() {
        return $this->hasMany(Report::class, 'project_id');
    }

    protected $table = 'project';
}