<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model {
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'reason',
        'content'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [];

    public function project() {
        return $this->belongsTo(Project::class, 'project');
    }

    public function user() {
        return $this->belongsTo(User::class, 'user_profile');
    }

    public function creator() {
        return $this->belongsTo(User::class, 'creator');
    }

    protected $table = 'report';
}