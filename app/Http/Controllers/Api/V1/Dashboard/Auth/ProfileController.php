<?php

namespace App\Http\Controllers\Api\V1\Dashboard\Auth;

use App\Http\Controllers\Controller;
use App\Services\Dashboard\ProfileService;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;



class ProfileController extends Controller
{
    use ResponseTrait;

    public function __construct(protected ProfileService $service)
    {
    }

    public function show()
    {
        return $this->showResponse(auth()->user());
    }
    public function update(Request $request)
    {
        try {
            $user = $this->service->update($request);
            return $this->showResponse($user);
        } catch (\Exception $e) {
            return $this->showError($e);
        }

    }
}