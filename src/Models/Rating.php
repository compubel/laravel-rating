<?php

namespace Compubel\Rating\Models;

use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{
    public function __construct(array $attributes = [])
    {
        if (! isset($this->table)) {
            $this->setTable(config('rating.table_name'));
        }

        parent::__construct($attributes);
    }
}
