<?php

namespace App\Http\Search\Filters;

use Illuminate\Database\Eloquent\Builder;

/**
 * This interface is used to implements a filter for the search engine
 */
interface Filter
{
    /**
     * Apply a given search value to the builder instance.
     * 
     * @param Builder $builder
     * @param mixed $value
     * @return Builder $builder
     */
    public static function apply(Builder $builder, $value);
}
