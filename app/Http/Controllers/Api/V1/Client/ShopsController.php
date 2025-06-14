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
        $shops = $this->shopsFilters->applyFilters(Shop::query())
            ->where('status', 'active')
            ->with([
                'products' => function ($query) {
                    $query->latest()->limit(10);
                }
            ])
            ->get()
            ->append(['logo_full_path', 'identity_front_face_full_path', 'identity_back_face_full_path', 'type_name'])
            // Append image to each product
            ->each(function ($shop) {
                $shop->products->each->append('full_path_image');
            });

        return $this->showResponse($shops, 'api.success');
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
        if (!$shop || $shop->status != 'active') {
            return response()->json([
                'message' => 'Shop not found'
            ], 404);
        }

        // Eager load the products relationship
        $shop->load('products');

        // Append 'full_path_image' to each product
        $shop->products->each->append('full_path_image');

        // Return the response with appended attributes
        return $this->showResponse(
            $shop->append([
                'logo_full_path',
                'identity_front_face_full_path',
                'identity_back_face_full_path',
                'type_name'
            ]),
            'api.success'
        );
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
