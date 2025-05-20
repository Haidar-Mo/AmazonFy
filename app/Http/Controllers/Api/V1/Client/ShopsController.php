<?php

namespace App\Http\Controllers\Api\V1\Client;

use App\Filters\ShopsFilters;
use App\Http\Controllers\Controller;
use App\Models\Shop;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;

class ShopsController extends Controller
{
    use ResponseTrait;

    public function __construct(
        protected ShopsFilters $shopsFilters,
    ) {
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $shops = $this->shopsFilters->applyFilters(Shop::query())->where('status', 'active')->paginate(20);
        return $this->showResponse($shops);
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
    public function show(Shop $shop)
    {
        if (!$shop | $shop->status != 'active') {
            return response()->json([
                'message' => 'Shop not found'
            ], 404);
        }
        return $this->showResponse($shop->load('products'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Shop $shop)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Shop $shop)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Shop $shop)
    {
        //
    }
}
