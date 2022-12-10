<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Storage;

class User extends Authenticatable {
    use Notifiable;

    const DEFAULT_IMAGE_PROVIDER_URL = 'https://picsum.photos/240';

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

    public function projects() {
        return $this->belongsToMany(
            Project::class,
            'project_member',
            'user_profile',
            'project'
        )->withPivot('is_favorite');
    }

    public function reports() {
        return $this->hasMany(Report::class, 'user_profile');
    }

    public function getProfilePicture() {
        return Storage::disk('public')->exists("users/$this->id") 
            ? Storage::url("public/users/$this->id") 
            : User::DEFAULT_IMAGE_PROVIDER_URL;
    }

    protected $table = 'user_profile';
}