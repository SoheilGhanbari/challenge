<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;

trait CommonModelTrait
{
   


    /**
     * Filters the results based on the request
     *
     * @param Builder $query
     * @param array $filter
     *
     * @return Builder
     */
    public function scopeFilter(Builder $query, array $filter): Builder
    {
        foreach ($filter as $value) {
            if ($value['left'] === 'date') {
                $query = $query->whereDate(
                    'created_at',
                    $value['operator'],
                    $value['right']
                );
            } else {
                $query = $query->where(
                    $value['left'],
                    $value['operator'],
                    $value['right']
                );
            }
        }
        return $query;
    }


}
