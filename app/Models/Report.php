<?php

namespace App\Models;

use App\Casts\Datetime;
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
        'reason'
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

    public function user() {
        return $this->belongsTo(User::class, 'user_profile_id');
    }

    public function creator() {
        return $this->belongsTo(User::class, 'creator_id')->withDefault(User::DELETED_USER);
    }

    protected $table = 'report';
}