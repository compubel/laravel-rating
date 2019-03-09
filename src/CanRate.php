<?php

namespace Compubel\Rating;

use Compubel\Rating\Contracts\Rating;
use Compubel\Rating\Contracts\Rateable;
use Compubel\Rating\Exceptions\ModelNotRateable;
use Compubel\Rating\Exceptions\RatingAlreadyExists;

trait CanRate
{
    /**
     * Relationship for models that this model currently rated.
     *
     * @param Model $model The model types of the results.
     * @return morphToMany The relationship.
     */
    public function ratings($model = null)
    {
        return $this->morphToMany(($model) ?: $this->getMorphClass(), 'rater', 'ratings', 'rater_id', 'rateable_id')
            ->withPivot('rateable_type', 'rating')
            ->wherePivot('rateable_type', ($model) ?: $this->getMorphClass())
            ->wherePivot('rater_type', $this->getMorphClass());
    }

    /**
     * Check if a model can be rated.
     *
     * @param Model $model The model to check
     * @return bool
     */
    public function isRateable($model): bool
    {
        if (! $model instanceof Rateable && ! $model instanceof Rating) {
            throw ModelNotRateable::create($model);
        }

        return true;
    }

    /**
     * Check if the current model is rating another model.
     *
     * @param Model $model The model which will be checked against.
     * @return bool
     */
    public function hasRated($model): bool
    {
        if (! $this->isRateable($model)) {
            return false;
        }

        return (bool) ! is_null($this->ratings($model->getMorphClass())->find($model->getKey()));
    }

    /**
     * Add rating for a certain model.
     *
     * @param Model $model The model which will be rated.
     * @param float $rating The rate amount.
     * @return bool
     */
    public function addRatingFor($model, $rating): bool
    {
        if (! $this->isRateable($model)) {
            return false;
        }

        if ($this->hasRated($model)) {
            throw RatingAlreadyExists::create($model);
        }

        $this->ratings()->attach($this->getKey(), [
            'rater_id' => $this->getKey(),
            'rateable_type' => $model->getMorphClass(),
            'rateable_id' => $model->getKey(),
            'rating' => (float) $rating,
        ]);

        return true;
    }

    /**
     * Update or add rating for a certain model.
     *
     * @param Model $model The model which will be rated.
     * @param float $rating The rate amount.
     * @return bool
     */
    public function updateRatingFor($model, $rating): bool
    {
        if (! $this->isRateable($model)) {
            return false;
        }

        if ($this->hasRated($model)) {
            $this->deleteRatingFor($model);
        }

        return $this->addRatingFor($model, $rating);
    }

    /**
     * Rate a certain model.
     *
     * @param Model $model The model which will be rated.
     * @param float $rating The rate amount.
     * @param bool $can_update Set to false if a rating can only be added once
     * @return bool
     */
    public function rate($model, $rating, $can_update = true): bool
    {
        if ($can_update === true) {
            return $this->updateRatingFor($model, $rating);
        }

        return $this->addRatingFor($model, $rating);
    }

    /**
     * Delete rating for a certain model.
     *
     * @param Model $model The model which will be unrated.
     * @return bool
     */
    public function deleteRatingFor($model): bool
    {
        if (! $this->isRateable($model)) {
            return false;
        }

        if (! $this->hasRated($model)) {
            return false;
        }

        return (bool) $this->ratings($model->getMorphClass())->detach($model->getKey());
    }

    /**
     * Unrate a certain model.
     *
     * @param Model $model The model which will be unrated.
     * @return bool
     */
    public function unrate($model): bool
    {
        return $this->deleteRatingFor($model);
    }
}
