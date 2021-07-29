<?php

namespace App\Http\Search;

use Illuminate\Database\Eloquent\Builder;

class SearchEngine
{
    /**
     * Apply query filters on a specific model
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

    /**
     * Apply query filters on a specific model
     * 
     * @param Builder $builder specify the model for the query
     * @param mixed $filters
     * @param String $modelName the name of the model (shortname)
     * @return Builder
     */
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

    /**
     * Get query filter for the model
     * 
     * @param   string  $filterName
     * @param   string  $modelName
     * @return string
     */
    public static function getFilter($filterName, $modelName)
    {
        $class = self::getFilterForModel($filterName, $modelName);
        if (self::isValidClass($class)) { // if specific filter doesnt exist go to global 
            return $class;
        } else {
            return self::getDefaultFilter($filterName);
        }
    }

    /**
     * Check if class exist
     * 
     * @param   string    $class
     * @return  boolean true if it exists, false otherwise
     */
    public static function isValidClass($class)
    {
        return class_exists($class);
    }

    /**
     * Get specific filter for the model
     * 
     * @param   string  $filterName
     * @param   string  $modelName
     * @return  string
     */
    public static function getFilterForModel($filterName, $modelName)
    {
        return __NAMESPACE__ . "\\Filters\\${modelName}\\" . str_replace(' ', '', ucwords(str_replace('_', ' ', $filterName)));
    }

    /**
     * Get global filter specific 
     * 
     * @param   string  filterName
     * @return  string
     */
    public static function getDefaultFilter($filterName)
    {
        return __NAMESPACE__ . "\\Filters\\" . str_replace(' ', '', ucwords(str_replace('_', ' ', $filterName)));
    }
}
