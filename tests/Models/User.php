<?php

namespace Compubel\Rating\Test\Models;

use Compubel\Rating\CanRate;
use Compubel\Rating\Contracts\Rater;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements Rater
{
    use CanRate;

    protected $fillable = [
        'name', 'email', 'password',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];
}
