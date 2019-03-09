<?php

namespace Compubel\Rating\Contracts;

interface Rateable
{
    public function raters($model = null);

    public function averageRating($model = null): float;

    public function countRatings($model = null): int;
}
