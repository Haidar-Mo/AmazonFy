<?php

namespace App\Http\Controllers\Api\V1\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\AirLineStoreRequest;
use App\Http\Requests\Dashboard\AirLineUpdateRequest;
use App\Http\Resources\AirLineResource;
use App\Models\AirLine;
use App\Services\Dashboard\AirLineService;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;

class AirLineController extends Controller
{
    use ResponseTrait;

    public function __construct(protected AirLineService $airLineService)
    {
    }


    public function index()
    {
        $airLines = $this->airLineService->list();

        return $this->showResponse(AirLineResource::collection($airLines));
    }

    public function store(AirLineStoreRequest $request)
    {
        $airLine = $this->airLineService->create($request->validated());

        return $this->showResponse(new AirLineResource($airLine));
    }


    public function show(AirLine $airLine)
    {
        return $this->showResponse(new AirLineResource(
            $airLine->load(['requiredFields'])
        ));
    }


    public function update(AirLineUpdateRequest $request, AirLine $airLine)
    {
        $airLine = $this->airLineService->update($airLine, $request->validated());

        return new AirLineResource($airLine);
    }


    public function destroy(AirLine $airLine)
    {
        $this->airLineService->delete($airLine);
        return $this->showMessage('airLine.delete_success');
    }

}
