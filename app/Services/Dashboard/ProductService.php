<?php

namespace App\Services\Dashboard;

use App\Models\Product;
use App\Traits\HasFiles;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;

/**
 * Class ProductService.
 */
class ProductService
{
    use HasFiles;
    public function index()
    {
        return Product::all()->append(['type_name', 'full_path_image']);
    }

    public function show(string $id)
    {
        return Product::findOrFail($id)->append(['type_name', 'full_path_image']);
    }

    public function store(FormRequest $request)
    {
        $data = $request->validated();
        if ($request->hasFile('image')) {
            $data['image'] = $this->saveFile($request->file('image'), 'Images/Products');
        }
        return Product::create($data)->append(['type_name', 'full_path_image']);
    }

    public function update(FormRequest $request, string $id)
    {
        $data = $request->validated();
        $product = Product::findOrFail($id);
        if ($request->hasFile('image')) {
            $data['image'] = $this->saveFile($request->file('image'), 'Images/Products');
            $this->deleteFile($product->image);
        }
        return DB::transaction(function () use ($data, $product) {
            $product->update($data);
            return $product->append(['type_name', 'full_path_image']);
        });
    }

    public function destroy(string $id)
    {
        $product = Product::findOrFail($id);
        DB::transaction(function () use ($product) {
            $this->deleteFile($product->image);
            $product->delete();
        });
    }
}
