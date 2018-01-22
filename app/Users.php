<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Users extends Authenticatable

{
    protected $table = 'user';
    protected $primaryKey = 'uid';
    public $timestamps = false;

    protected $fillable = [
        'username','pwd','password','qq','email','mobile'
    ];
}