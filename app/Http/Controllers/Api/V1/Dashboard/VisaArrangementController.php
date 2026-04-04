<?php

namespace App\Http\Controllers\Api\V1\Dashboard;

use App\Http\Controllers\Controller;
use App\Services\Dashboard\VisaArrangementService;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;

class VisaArrangementController extends Controller
{

    use ResponseTrait;

    public function __construct(protected VisaArrangementService $service)
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


    public function accept(string $id)
    {
        try {
            $data = $this->service->accept($id);
            return $this->showResponse($data);
        } catch (\Exception $e) {
            return $this->showError($e);
        }

    }
    public function reject(string $id)
    {
        try {
            $data = $this->service->reject($id);
            return $this->showResponse($data);
        } catch (\Exception $e) {
            return $this->showError($e);
        }
    }
}
