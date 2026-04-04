<?php

namespace App\Services\Dashboard;

use App\Http\Resources\VisaRequestResource;
use App\Models\VisaRequest;
use Illuminate\Http\Request;

/**
 * Class VisaRequestService.
 */
class VisaRequestService
{

    public function list()
    {
        $list = VisaRequest::with(['user', 'visa'])
            ->orderBy('created_at', 'desc')
            ->get()
            ->append(['shop_name']);
        return $list;
    }

    public function show(string $id)
    {
        $request = VisaRequest::with(['user', 'visa', 'fields'])
            ->findOrFail($id)
            ->append(['shop_name'])
            ->makeVisible(['user', 'visa']);
        return $request;
    }

    public function delete(string $id)
    {
        VisaRequest::findOrFail($id)->delete();
    }

    public function changeStatus(string $id, Request $request)
    {
        $status = $request->input('status');
        if (!in_array($status, ['accepted', 'rejected']))
            throw new \Exception("Invalid inserted status", 400);

        $visa_request = VisaRequest::with(['user', 'visa', 'fields'])
            ->findOrFail($id)
            ->makeVisible(['user', 'visa']);

        $visa_request->update(['status' => $status]);
        return $visa_request;
    }
}
