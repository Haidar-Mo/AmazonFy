<?php
namespace App\Http\Controllers\Api\V1\Dashboard;

use App\Http\Controllers\Controller;
use App\Services\Dashboard\OrderService;
use App\Traits\ResponseTrait;

class OrderController extends Controller
{
    use ResponseTrait;

    public function __construct(public OrderService $service)
    {
    }

    public function show(string $id)
    {
        try {
            $order = $this->service->show($id);
            return $this->showResponse($order, 'order.show_success');
        } catch (\Exception $e) {
            return $this->showError($e, 'order.errors.show_error');
        }
    }

    public function index()
    {
        try {
            $orders = $this->service->index();
            return $this->showResponse($orders, 'order.index_success', [], 200);
        } catch (\Exception $e) {
            return $this->showError($e, 'order.errors.index_error');
        }
    }

    public function update(string $id)
    {
        try {
            $order = $this->service->updateStatus($id);
            return $this->showResponse($order, 'order.update_success');
        } catch (\Exception $e) {
            return $this->showError($e, 'order.errors.update_error');
        }
    }

    public function destroy(string $id)
    {
        try {
            $this->service->destroy($id);
            return $this->showMessage('order.delete_success', [], 200);
        } catch (\Exception $e) {
            return $this->showError($e, 'order.errors.delete_error');
        }
    }
}
