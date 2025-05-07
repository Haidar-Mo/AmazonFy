<?php

namespace App\Services\Dashboard;

use App\Models\Product;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Class ProductService.
 */
class ProductService
{
    public function index()
    {
        return Product::all();
    }

    public function show(string $id)
    {
        return Product::findOrFail($id);
    }

    public function store(FormRequest $request)
    {
        $data = $request->validated();
        return Product::create($data);
    }

    public function update(FormRequest $request, string $id)
    {
        $data = $request->validated();
        $product = Product::findOrFail($id);
        $product->update($data);
        return $product;
    }

    public function destroy(string $id)
    {
        Product::findOrFail($id)->delete();
    }
}
