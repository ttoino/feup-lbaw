<?php

namespace App\Models;

use App\Casts\Datetime;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model {
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'json',
        'type',
        'notifiable_id',
        'read_date',
        'creation_date'
    ];

    protected $casts = [
        'creation_date' => Datetime::class,
        'read_date' => Datetime::class,
        'json' => 'array',
    ];

    protected $table = 'notification';
}