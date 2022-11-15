<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model {
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'description',
        'color'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [];

    public function project() {
        return $this->belongsTo(
            Project::class,
            'project'
        );
    }

    public function tasks() {
        return $this->belongsToMany(
            Task::class,
            'task_tag',
            'tag',
            'task'
        );
    }

    protected $table = 'tag';
}