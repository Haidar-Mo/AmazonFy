<?php

namespace App\Http\Controllers\Api\V1\Dashboard;

use App\Http\Controllers\Controller;
use App\Services\Dashboard\StatisticsService;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;

class StatisticsController extends Controller
{
    use ResponseTrait;

    public function __construct(public StatisticsService $service)
    {
    }

    public function show(Request $request)
    {
        try {
            $request->validate([
                'type' => 'required',
                'first_date' => 'required',
                'second_date' => 'required',
            ]);

            $data = $this->service->statistics($request);
            return $this->showResponse($data, 'statistics.success');
        } catch (\Exception $e) {
            report($e);
            return $this->showError($e, 'statistics.errors.failed');
        }
    }
}
