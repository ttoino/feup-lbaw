<?php

namespace App\Models;

use App\Events\UserCreated;
use App\Events\UserDeleted;
use App\Events\UserUpdated;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Log;

class User extends Authenticatable {
    use Notifiable, HasFactory;

    // Don't add create and update timestamps in database.
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'blocked',
        'is_admin'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token'
    ];

    protected $dispatchesEvents = [
        'created' => UserCreated::class,
        'updated' => UserUpdated::class,
        'deleted' => UserDeleted::class
    ];

    public function projects() {
        return $this->belongsToMany(
            Project::class,
            'project_member',
            'user_profile_id',
            'project_id'
        )->withPivot('is_favorite');
    }

    public function reports() {
        return $this->hasMany(Report::class, 'user_profile_id');
    }

    public function getProfilePicture() {
        return Storage::disk('public')->exists("users/$this->id.webp")
            ? Storage::url("public/users/$this->id.webp")
            : Storage::url("public/users/default_$this->id.svg");
    }

    protected $table = 'user_profile';
}