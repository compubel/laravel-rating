<?php

namespace Compubel\Rating\Test\Models;

use Compubel\Rating\Rate;
use Compubel\Rating\Contracts\Rating;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Member extends Authenticatable implements Rating
{
    use Rate;

    protected $fillable = [
        'name',
    ];
}
