<?php

namespace App\Models;

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

    public function data() {
        return json_decode($this->json);
    }

    protected $table = 'notification';
}