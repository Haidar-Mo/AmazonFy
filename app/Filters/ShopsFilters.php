<?php

namespace App\Filters;


use App\Filters\BaseFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class ShopsFilters extends BaseFilter
{
    public function typeId(Builder $query): Builder
    {
        if ($this->request->filled('typeId'))
            $query->where('shop_type_id', $this->request->input('typeId'));
        return $query;
    }
}
