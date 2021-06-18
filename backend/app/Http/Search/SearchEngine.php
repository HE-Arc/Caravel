<?php

namespace App\Http\Search;

use Illuminate\Database\Eloquent\Builder;

class SearchEngine
{
    /**
     * Apply query fitlers on a specific model
     * 
     * @param Builder $builder specify the model for the query
     * @param mixed $filters
     * @param String $modelName the name of the model (shortname)
     * @return Builder
     */
    public static function applyFilters(Builder $builder, $filters, $modelName)
    {
        $query = SearchEngine::applyFilterToQuery($builder, $filters, $modelName);

        return $query;
    }

    public static function applyFilterToQuery(Builder $query, $filters, $modelName)
    {
        foreach ($filters as $filter => $value) {
            $class = self::getFilter($filter, $modelName);
            if (self::isValidClass($class)) {
                $query = $class::apply($query, $value);
            }
        }

        return $query;
    }

    public static function getFilter($filterName, $modelName)
    {
        $class = self::getFilterForModel($filterName, $modelName);
        if (self::isValidClass($class)) {
            return $class;
        } else {
            return self::getDefaultFilter($filterName);
        }
    }

    public static function isValidClass($class)
    {
        return class_exists($class);
    }

    public static function getFilterForModel($filterName, $modelName)
    {
        return __NAMESPACE__ . "\\Filters\\${modelName}\\" . str_replace(' ', '', ucwords(str_replace('_', ' ', $filterName)));
    }

    public static function getDefaultFilter($filterName)
    {
        return __NAMESPACE__ . "\\Filters\\" . str_replace(' ', '', ucwords(str_replace('_', ' ', $filterName)));
    }
}
