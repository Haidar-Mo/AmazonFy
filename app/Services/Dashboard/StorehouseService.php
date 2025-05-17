<?php

namespace App\Services\Dashboard;

use App\Models\Storehouse;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;

/**
 * Class StorehouseService.
 */
class StorehouseService
{

    public function store(FormRequest $request)
    {
        $data = $request->validated();
        return DB::transaction(function () use ($data) {
            $storehouse = Storehouse::create($data);
            return $storehouse->append(['region_name']);
        });
    }

    public function destroy(string $id)
    {
        $store = Storehouse::findOrFail($id);
        DB::transaction(function () use ($store) {
            $store->delete();
        });
    }
}
