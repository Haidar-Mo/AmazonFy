<?php

namespace App\Filters;


use App\Filters\BaseFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class OrdersFilters extends BaseFilter
{
    public function status(Builder $query): Builder
    {
        if ($this->request->filled('status'))
            return $query->where('status', $this->request->input('status'));
        return $query;

    }
}
