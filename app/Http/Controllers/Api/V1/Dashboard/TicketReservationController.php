<?php

namespace App\Http\Controllers\Api\V1\Dashboard;

use App\Http\Controllers\Controller;
use App\Services\Dashboard\TicketService;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;

class TicketReservationController extends Controller
{
    use ResponseTrait;

    public function __construct(private TicketService $service)
    {
    }

    public function index()
    {
        try {
            $data = $this->service->list();
            return $this->showResponse($data);
        } catch (\Exception $e) {
            return $this->showError($e);
        }
    }

    public function show(string $id)
    {
        try {
            $data = $this->service->show($id);
            return $this->showResponse($data);
        } catch (\Exception $e) {
            return $this->showError($e);
        }
    }

    public function destroy(string $id)
    {
        try {
            $this->service->delete($id);
            return $this->showMessage('reservation.request.delete-success');
        } catch (\Exception $e) {
            return $this->showError($e, 'reservation.request.delete-error');
        }
    }

    public function changeStatus(string $id, Request $request)
    {
        try {
            $request->validate([
                'status' => 'required|string|in:accepted,rejected',
            ]);

            $data = $this->service->changeStatus($id, $request);
            return $this->showResponse($data, 'reservation.request.status-success');
        } catch (\Exception $e) {
            return $this->showError($e, 'reservation.request.status-error');
        }
    }
}
