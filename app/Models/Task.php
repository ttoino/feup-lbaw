<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\LaravelMarkdown\MarkdownRenderer;

class Task extends Model {
    use Notifiable, HasFactory;
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
        'completed',
        'task_group_id',
        'creator_id',
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

    protected $casts = [
        'completed' => 'boolean'
    ];

    protected $appends = ['description_formatted'];

    protected $with = ['tags', 'comments', 'creator', 'assignees'];

    public function project() {
        return $this->hasOneThrough(
            Project::class,
            TaskGroup::class,
            'id',
            'id',
            'task_group_id',
            'project_id'
        );
    }

    public function taskGroup() {
        return $this->belongsTo(
            TaskGroup::class,
            'task_group_id'
        );
    }

    public function creator() {
        return $this->belongsTo(User::class, 'creator_id');
    }

    public function comments() {
        return $this->hasMany(
            TaskComment::class,
            'task_id'
        );
    }

    public function tags() {
        return $this->belongsToMany(
            Tag::class,
            'task_tag',
            'task_id',
            'tag_id'
        );
    }

    public function assignees() {
        return $this->belongsToMany(
            User::class,
            'task_assignee',
            'task_id',
            'user_profile_id'
        );
    }

    public function descriptionFormatted(): Attribute {
        return Attribute::make(fn($_, $attributes) => app(MarkdownRenderer::class)->toHtml($attributes['description'] ?? ""));
    }

    protected $table = 'task';
}