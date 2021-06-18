<?php

namespace App\Http\Search\Filters\Task;

use Illuminate\Database\Eloquent\Builder;
use App\Http\Search\Filters\Filter;

class Text implements Filter
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
        $text = "%$value%";
        return $builder->where(function ($query) use ($text) {
            $query->where('title', 'LIKE', $text)
                ->orWhere('description', 'LIKE', $text);
        });
    }
}
