<?php

namespace App\Models;

use App\Events\UserCreated;
use App\Events\UserDeleted;
use App\Events\UserUpdated;
use Illuminate\Database\Eloquent\Casts\Attribute;
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

    protected $casts = [
        'is_admin' => 'boolean',
    ];

    protected $appends = [
        'profile_pic'
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

    protected function profilePic(): Attribute {
        return Attribute::make(fn($_, $attributes) => Storage::disk('public')->exists("users/{$attributes['id']}.webp")
            ? Storage::url("public/users/{$attributes['id']}.webp")
            : Storage::url("public/users/default_{$attributes['id']}.svg"));
    }

    protected $table = 'user_profile';
}