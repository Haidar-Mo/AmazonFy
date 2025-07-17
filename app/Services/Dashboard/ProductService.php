<?php

namespace App\Services\Dashboard;

use App\Models\Product;
use App\Traits\HasFiles;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

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

    public function indexPaginate()
    {
        return Product::paginate()->through(function ($product) {
            return $product->append(['type_name', 'full_path_image']);
        });
    }

    public function show(string $id)
    {

        return Product::findOrFail($id)->append(['type_name', 'full_path_image']);
    }

    public function store(FormRequest $request)
    {
        $data = $request->validated();
        $data['image'] = $this->saveFile($request->file('image'), 'Images/Products');

        $product = Product::create([
            'image' => $data['image'],
            'type_id' => $data['type_id'],
            'wholesale_price' => $data['wholesale_price'],
            'selling_price' => $data['selling_price'],
            'is_available' => $data['is_available'] ?? true,
        ]);

        if (isset($data['title_ar'])) {
            $product->translations()->create(
                [
                    'locale' => 'ar',
                    'title' => $data['title_ar'],
                    'details' => $data['details_ar'],
                ]
            );
        }

        if (isset($data['title_en'])) {
            $product->translations()->create(
                [
                    'locale' => 'en',
                    'title' => $data['title_en'],
                    'details' => $data['details_en'],
                ]
            );
        }
        return $product->append(['type_name', 'full_path_image']);
    }

    public function update(FormRequest $request, string $id)
    {
        $data = $request->validated();
        $product = Product::findOrFail($id);
        if ($request->hasFile('image')) {
            $data['image'] = $this->saveFile($request->file('image'), 'Images/Products');
            $this->deleteFile($product->image);
        }
        return DB::transaction(function () use ($data, $request, $product) {
            $product->update(array_merge(
                $request->only('type_id', 'selling_price', 'wholesale_price', 'is_available'),
                isset($data['image']) ? ['image' => $data['image']] : []
            ));
            if (isset($data['title_ar'])) {
                $product->translateOrNew('ar')->title = $data['title_ar'];
            }

            if (isset($data['details_ar'])) {
                $product->translateOrNew('ar')->details = $data['details_ar'];
            }

            if (isset($data['title_en'])) {
                $product->translateOrNew('en')->title = $data['title_en'];
            }

            if (isset($data['details_en'])) {
                $product->translateOrNew('en')->details = $data['details_en'];
            }

            $product->save();

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
