<?php

namespace App\Filters;


use App\Filters\BaseFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class ProductsFilters extends BaseFilter
{
    public function typeId(Builder $query): Builder
    {
        if ($this->request->filled('typeId'))
            $query->where('type_id', $this->request->input('typeId'));
        return $query;
    }

    public function search(Builder $query): Builder
    {
        if ($this->request->filled('search'))
            return $query->where('title', 'like', '%' . $this->request->input('search') . '%');
        return $query;
    }
}
