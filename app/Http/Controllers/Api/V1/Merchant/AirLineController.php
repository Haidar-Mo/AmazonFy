<?php

namespace App\Http\Controllers\Api\V1\Merchant;

use App\Http\Controllers\Controller;
use App\Http\Resources\AirLineResource;
use App\Models\AirLine;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;

class AirLineController extends Controller
{
    use ResponseTrait;

    public function index()
    {
        return $this->showResponse(AirLine::all());
    }


    public function show(string $id)
    {
        $airLine = AirLine::with(['requiredFields'])->findOrFail($id);
        return $this->showResponse(new AirLineResource($airLine));
    }
}
