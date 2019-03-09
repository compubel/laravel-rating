<?php

namespace Compubel\Rating\Exceptions;

use InvalidArgumentException;
use Compubel\Rating\Contracts\Rateable;

class ModelNotRateable extends InvalidArgumentException
{
    public static function create(string $className)
    {
        return new static('The given model class `'.$className.'` does not implement `'.Rateable::class.'` contract');
    }
}
