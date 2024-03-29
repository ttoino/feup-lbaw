<?php

namespace App\Models;

use App\Events\UserCreated;
use App\Events\UserDeleted;
use App\Events\UserUpdated;
use App\Listeners\CreateDefaultProfilePic;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class User extends Authenticatable implements MustVerifyEmail {
    use Notifiable, HasFactory;

    const DELETED_USER = [
        'id' => 0,
        'name' => 'Deleted user',
        'email' => 'Deleted user',
        'profile_picture_path' => 'public/logo.svg'
    ];

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
        'is_admin',
        'profile_picture_path'
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

    protected $casts = [
        'is_admin' => 'boolean',
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
        )->withPivot('is_favorite')->orderByPivot('is_favorite', 'desc');
    }

    public function reports() {
        return $this->hasMany(Report::class, 'user_profile_id');
    }
    
    public function oAuthProfiles() {
        return $this->hasMany(OAuthUser::class, 'user_id');
    }
    
    public function notifications() {
        return $this->hasMany(Notification::class, 'notifiable_id')->orderByDesc('creation_date');
    }

    protected function profilePic(): Attribute {
        return Attribute::make(get: function ($_, $attributes) {

            if ($attributes['profile_picture_path'] !== null)
                return $attributes['profile_picture_path'];

            if (Storage::exists("public/users/{$attributes['id']}.webp"))
                return Storage::url("public/users/{$attributes['id']}.webp");

            if (!Storage::exists("public/users/default_{$attributes['id']}.svg"))
                (new CreateDefaultProfilePic())->handle(new UserUpdated($this));

            return Storage::url("public/users/default_{$attributes['id']}.svg");
        });
    }

    protected $table = 'user_profile';
}