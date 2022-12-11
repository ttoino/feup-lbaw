<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ThreadComment extends Model {
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

    public function thread() {
        return $this->belongsTo(
            Thread::class,
            'thread_id'
        );
    }

    public function author() {
        return $this->belongsTo(User::class, 'author_id');
    }

    protected $table = 'thread_comment';
}