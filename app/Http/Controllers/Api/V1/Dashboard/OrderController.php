<?php
namespace App\Http\Controllers\Api\V1\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\OrderCreateRequest;
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

    public function store(OrderCreateRequest $request)
    {
        try {
            $this->service->createOrder($request);
            return $this->showMessage('order.create_success');
            // return $this->showResponse($order, 'order.create_success');
        } catch (\Exception $e) {
            return $this->showError($e, 'order.errors.create_error');
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

    public function cancel(string $id)
    {
        try {
            $this->service->cancel($id);
            return $this->showMessage('order.canceled_success', [], 200);
        } catch (\Exception $e) {
            return $this->showError($e, 'order.errors.cancel_error');
        }
    }
}
