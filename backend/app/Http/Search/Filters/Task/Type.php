<?php

namespace App\Http\Search\Filters\Task;

use Illuminate\Database\Eloquent\Builder;
use App\Http\Search\Filters\Filter;

class Type implements Filter
{
    /**
     * Apply a given search value to the builder instance.
     *
     * @param Builder $builder
     * @param mixed $value
     * @return Builder $builder
     */
    public static function apply(Builder $builder, $value)
    {
        return $builder->where('tasktype_id', '=', $value);
    }
}
