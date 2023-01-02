<?php

namespace App\Models;

use App\Casts\Datetime;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectTimelineAction extends Model {
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'description'
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

    protected $table = 'project_timeline_action';
}