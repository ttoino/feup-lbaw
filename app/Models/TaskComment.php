<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\LaravelMarkdown\MarkdownRenderer;

class TaskComment extends Model {
    use HasFactory;
    const CREATED_AT = 'creation_date';
    const UPDATED_AT = 'edit_date';

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

    protected $appends = ['content_formatted'];

    public function task() {
        return $this->belongsTo(
            Task::class,
            'task_id'
        );
    }

    public function author() {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function contentFormatted(): Attribute {
        return Attribute::make(fn($_, $attributes) => app(MarkdownRenderer::class)->toHtml($attributes['content'] ?? ""));
    }

    protected $table = 'task_comment';
}