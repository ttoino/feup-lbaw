<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TaskComment extends Model {
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

    public function task() {
        return $this->belongsTo(
            Task::class,
            'task'
        );
    }

    public function author() {
        return $this->belongsTo(User::class, 'author_id');
    }

    protected $table = 'task_comment';
}