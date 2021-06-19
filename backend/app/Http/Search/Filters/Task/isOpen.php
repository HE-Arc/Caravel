<?php

namespace App\Http\Search\Filters\Task;

use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;
use App\Http\Search\Filters\Filter;

class isOpen implements Filter
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
        return $value ? $builder->where('due_at', '>=', Carbon::now()->startOfDay()) : $builder->where('due_at', '<', Carbon::now()->startOfDay());
    }
}
