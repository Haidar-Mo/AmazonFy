<?php

namespace App\Http\Controllers\Api\V1\Merchant;

use App\Http\Controllers\Controller;
use App\Http\Resources\VisaResource;
use App\Models\Visa;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;

class VisaController extends Controller
{
    use ResponseTrait;


    public function index()
    {
        return $this->showResponse(Visa::all());
    }


    public function show(string $id)
    {
        $visa = Visa::with(['requiredFields'])->findOrFail($id);
        return $this->showResponse(new VisaResource($visa));
    }

    
}
