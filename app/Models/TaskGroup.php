<?php

namespace App\Models;

use App\Casts\Datetime;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TaskGroup extends Model {
    
    use HasFactory;
    
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'description',
        'position'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [];

    protected $casts = [
        'creation_date' => Datetime::class
    ];

    public function project() {
        return $this->belongsTo(Project::class, 'project_id');
    }

    public function tasks() {
        return $this->hasMany(
            Task::class,
            'task_group_id'
        )->orderBy('position');
    }

    protected $table = 'task_group';
}