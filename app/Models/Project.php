<?php

namespace App\Models;

use App\Casts\Datetime;
use App\Casts\Markdown;
use App\Events\ProjectDeleted;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\LaravelMarkdown\MarkdownRenderer;

class Project extends Model {
    use HasFactory;

    public $timestamps = false;

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

    protected $casts = [
        'creation_date' => Datetime::class,
        'last_modification_date' => Datetime::class,
        'description' => Markdown::class
    ];

    protected $dispatchesEvents = [
        'deleting' => ProjectDeleted::class
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
        )->orderBy('creation_date', "desc");
    }

    public function reports() {
        return $this->hasMany(Report::class, 'project_id');
    }

    protected $table = 'project';
}