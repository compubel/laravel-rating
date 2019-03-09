<?php

namespace Compubel\Rating\Test\Models;

use Compubel\Rating\CanBeRated;
use Compubel\Rating\Contracts\Rateable;
use Illuminate\Database\Eloquent\Model;

class Post extends Model implements Rateable
{
    use CanBeRated;

    protected $fillable = [
        'name',
    ];
}
