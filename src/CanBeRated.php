<?php

namespace Compubel\Rating;

use Compubel\Rating\Contracts\Rateable;

trait CanBeRated
{
    /**
     * Relationship for models that rated this model.
     *
     * @param Model $model The model types of the results.
     * @return morphToMany The relationship.
     */
    public function raters($model = null)
    {
        return $this->morphToMany(($model) ?: $this->getMorphClass(), 'rateable', 'ratings', 'rateable_id', 'rater_id')
            ->withPivot('rater_type', 'rating')
            ->wherePivot('rater_type', ($model) ?: $this->getMorphClass())
            ->wherePivot('rateable_type', $this->getMorphClass());
    }

    /**
     * Calculate the average rating of the current model.
     *
     * @return float The average rating.
     */
    public function averageRating($model = null): float
    {
        if ($this->raters($model)->count() == 0) {
            return (float) 0.00;
        }

        return (float) $this->raters($model)->avg('rating');
    }

    /**
     * Count the ratings of the current model.
     *
     * @return int The ratings count.
     */
    public function countRatings($model = null): int
    {
        if ($this->raters($model)->count() == 0) {
            return (int) 0;
        }

        return (int) $this->raters($model)->count('rating');
    }
}
