<?php

namespace Compubel\Rating\Exceptions;

use InvalidArgumentException;

class RatingAlreadyExists extends InvalidArgumentException
{
    public static function create(string $className)
    {
        return new static('The given model class `'.$className.'` has already been rated.');
    }
}
