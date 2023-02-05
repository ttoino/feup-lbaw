<?php

namespace App\Models;

use App\Casts\Datetime;
use App\Casts\Markdown;
use App\Events\ThreadCreated;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Spatie\LaravelMarkdown\MarkdownRenderer;

class Thread extends Model {
    use HasFactory;

    protected $CREATED_AT = 'creation_date';
    protected $UPDATED_AT = 'edit_date';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
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
        'created' => ThreadCreated::class
    ];

    protected $appends = ['editable'];

    public function project() {
        return $this->belongsTo(
                Project::class,
            'project_id'
        );
    }

    public function comments() {
        return $this->hasMany(
                ThreadComment::class,
            'thread_id'
        );
    }

    public function author() {
        return $this->belongsTo(User::class, 'author_id')->withDefault(User::DELETED_USER);
    }

    protected function editable(): Attribute {
        return Attribute::make(get: fn() => Auth::user()?->can('update', $this));
    }

    protected $table = 'thread';
}