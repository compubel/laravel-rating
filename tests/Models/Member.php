<?php

namespace Compubel\Rating\Test\Models;

use Compubel\Rating\Contracts\Rating;
use Compubel\Rating\Rate;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Member extends Authenticatable implements Rating
{
    use Rate;

    protected $fillable = [
        'name',
    ];
}
