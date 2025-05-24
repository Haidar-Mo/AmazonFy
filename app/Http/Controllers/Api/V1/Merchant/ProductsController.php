<?php

namespace App\Http\Controllers\Api\V1\Merchant;

use App\Filters\ProductsFilters;
use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Shop;
use App\Traits\ResponseTrait;
use Auth;
use DB;
use Illuminate\Http\Request;

class ProductsController extends Controller
{
    use ResponseTrait;

    public function __construct(
        protected ProductsFilters $productsFilters,
    ) {
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = $this->productsFilters->applyFilters(Product::query())->get()->append('full_path_image');
        return $this->showResponse($products);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Shop $shop)
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        return DB::transaction(function () use ($request) {
            $shop = Shop::where('user_id', $request->user()->id)->firstOrFail();
            $request->validate(['product_id' => ['required', 'exists:products,id']]);
            $product = Product::findOrFail($request->product_id);
            $shop->products()->attach($product);
            return $this->showMessage('Added successfully');
        });
    }

    /**
     * Display the specified resource.
     */
    public function show(Shop $shop, Product $product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Shop $shop, Product $product)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Shop $shop, Product $product)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        return DB::transaction(function () use ($product) {
            $shop = Shop::where('user_id', Auth::user()->id)->firstOrFail();
            $shop->products()->detach($product);
            return $this->showMessage('Deleted successfully');
        });
    }
}
