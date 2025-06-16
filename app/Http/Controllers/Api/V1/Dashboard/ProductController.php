<?php
namespace App\Http\Controllers\Api\V1\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\ProductCreateRequest;
use App\Http\Requests\Dashboard\ProductUpdateRequest;
use App\Services\Dashboard\ProductService;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    use ResponseTrait;

    public function __construct(public ProductService $service)
    {
    }

    public function index()
    {
        try {
            $products = $this->service->index();
            return $this->showResponse($products, 'product.index_success');
        } catch (\Exception $e) {
            return $this->showError($e, 'product.errors.index_error');
        }
    }

    public function show(string $id)
    {
        try {
            $product = $this->service->show($id);
            return $this->showResponse($product, 'product.show_success');
        } catch (\Exception $e) {
            return $this->showError($e, 'product.errors.show_error');
        }
    }

    public function store(ProductCreateRequest $request)
    {
        try {
            $product = $this->service->store($request);
            return $this->showResponse($product, 'product.create_success');
        } catch (\Exception $e) {
            return $this->showError($e, 'product.errors.create_error');
        }
    }
    public function localeStore(Request $request)
    {
       
        try {
            $product = $this->service->localeStore($request);
            return $this->showResponse($product, 'product.create_success');
        } catch (\Exception $e) {
            return $this->showError($e, 'product.errors.create_error');
        }
    }

    public function update(ProductUpdateRequest $request, string $id)
    {
        try {
            $product = $this->service->update($request, $id);
            return $this->showResponse($product, 'product.update_success');
        } catch (\Exception $e) {
            return $this->showError($e, 'product.errors.update_error');
        }
    }

    public function destroy(string $id)
    {
        try {
            $this->service->destroy($id);
            return $this->showMessage('product.delete_success');
        } catch (\Exception $e) {
            return $this->showError($e, 'product.errors.delete_error');
        }
    }
}
