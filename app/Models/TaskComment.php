<?php

namespace App\Models;

use App\Casts\Datetime;
use App\Casts\Markdown;
use App\Events\TaskCommentCreated;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Auth;
use Spatie\LaravelMarkdown\MarkdownRenderer;

class TaskComment extends Model {
    use HasFactory;

    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'content'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [];

    protected $with = ['author'];

    protected $casts = [
        'creation_date' => Datetime::class,
        'edit_date' => Datetime::class,
        'content' => Markdown::class
    ];

    protected $dispatchesEvents = [
        'created' => TaskCommentCreated::class
    ];

    protected $appends = ['editable'];

    public function task() {
        return $this->belongsTo(
                Task::class,
            'task_id'
        );
    }

    public function author() {
        return $this->belongsTo(User::class, 'author_id')->withDefault(User::DELETED_USER);
    }

    protected function editable(): Attribute {
        return Attribute::make(fn() => Auth::user()?->can('update', $this));
    }

    protected $table = 'task_comment';
}