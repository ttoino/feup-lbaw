<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Tag extends Model {
    use HasFactory;
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
            'project_id'
        );
    }

    public function tasks() {
        return $this->belongsToMany(
            Task::class,
            'task_tag',
            'tag_id',
            'task_id'
        );
    }

    public function rgbColor() {
        return ($this->color >> 16) . ', ' . (($this->color >> 8) & 255) . ', ' . ($this->color & 255);
    }

    protected $table = 'tag';
}