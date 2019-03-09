<?php

namespace Compubel\Rating\Contracts;

interface Rater
{
    public function ratings($model = null);

    public function isRateable($model): bool;

    public function hasRated($model): bool;

    public function addRatingFor($model, $rating): bool;

    public function updateRatingFor($model, $rating): bool;

    public function rate($model, $rating, $can_update): bool;

    public function deleteRatingFor($model): bool;

    public function unrate($model): bool;
}
