<?php

namespace App\Http\Controllers\Api\V1\Dashboard\Auth;

use App\Http\Controllers\Controller;
use App\Services\CodeService;
use App\Traits\{
    HasFiles
};
use App\Traits\ResponseTrait;


class RegisterController extends Controller
{
    use HasFiles, ResponseTrait;

    public function __construct(protected CodeService $codeService)
    {

    }



}
