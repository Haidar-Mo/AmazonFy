<?php

namespace App\Http\Controllers\Api\V1\Dashboard;

use App\Http\Controllers\Controller;
use App\Services\Dashboard\VisaRequestService;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;

class VisaRequestController extends Controller
{
    use ResponseTrait;

    public function __construct(protected VisaRequestService $service)
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

    public function store()
    {

    }

    public function update(string $id)
    {

    }

    public function delete(string $id)
    {
        try {
            $this->service->delete($id);
            return $this->showMessage('visa.request.delete-success');
        } catch (\Exception $e) {
            return $this->showError($e, 'visa.request.delete-error');
        }
    }

    public function updateStatus(string $id, Request $request)
    {

        try {
            $request->validate([
                'status' => 'required|string|in:accepted,rejected',
            ]);

            $data = $this->service->changeStatus($id, $request);
            return $this->showResponse($data, 'visa.request.status-success');
        } catch (\Exception $e) {
            return $this->showError($e, 'visa.request.status-error');
        }
    }
}
