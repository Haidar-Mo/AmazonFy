<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\ShopType;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;

class ShopTypesController extends Controller
{
    use ResponseTrait;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $types = ShopType::select(['id', 'name'])
            ->withCount('shops') // Adds a 'shops_count' attribute
            ->orderByDesc('shops_count') // Sort by count descending
            ->get()
            ->makeHidden('shops_count'); // Optional: Hide the count from the response

        return $this->showResponse($types);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(ShopType $shopType)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ShopType $shopType)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ShopType $shopType)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ShopType $shopType)
    {
        //
    }
}
