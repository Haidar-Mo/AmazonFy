<?php

namespace App\Http\Controllers\Api\V1\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\StoreVisaRequest;
use App\Http\Requests\Dashboard\UpdateVisaRequest;
use App\Http\Resources\VisaResource;
use App\Models\Visa;
use App\Services\Dashboard\VisaService;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;

class VisaController extends Controller
{
    use ResponseTrait;
    protected VisaService $visaService;

    public function __construct(VisaService $visaService)
    {
        $this->visaService = $visaService;
    }


    public function index()
    {
        $visas = $this->visaService->list();

        return $this->showResponse(VisaResource::collection($visas));
    }


    public function store(StoreVisaRequest $request)
    {
        $visa = $this->visaService->create($request->validated());

        return $this->showResponse(new VisaResource($visa));
    }


    public function show(Visa $visa)
    {
        return $this->showResponse(new VisaResource(
            $visa->load(['requiredFields'])
        ));
    }


    public function update(UpdateVisaRequest $request, Visa $visa)
    {
        $visa = $this->visaService->update($visa, $request->validated());

        return new VisaResource($visa);
    }


    public function destroy(Visa $visa)
    {
        $this->visaService->delete($visa);
        return $this->showMessage('visa.delete_success');
    }
}
