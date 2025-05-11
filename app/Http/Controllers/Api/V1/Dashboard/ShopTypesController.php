<?php

namespace App\Http\Controllers\Api\V1\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\ShopType;
use App\Traits\ResponseTrait;
use DB;
use Illuminate\Http\Request;

class ShopTypesController extends Controller
{
    use ResponseTrait;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $types = ShopType::all();
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
        return DB::transaction(function () use ($request) {
            $request->validate([
                'name' => ['required', 'string']
            ]);
            $type = ShopType::create([
                'name' => $request->name
            ]);

            return response()->json([
                'message' => 'Type created successfully',
                'type' => $type
            ], 201);
        });

    }

    /**
     * Display the specified resource.
     */
    public function show(ShopType $shopType)
    {
        return $this->showResponse($shopType);
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
        return DB::transaction(function () use ($request, $shopType) {
            $shopType->update([
                'name' => $request->name
            ]);

            return $this->showResponse($shopType);
        });

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ShopType $shopType)
    {
        return DB::transaction(function () use ($shopType) {
            $shopType->delete();
            return $this->showMessage('Deleted successfully');
        });
    }
}
