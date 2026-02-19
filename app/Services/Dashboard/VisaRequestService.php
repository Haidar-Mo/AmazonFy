<?php

namespace App\Services\Dashboard;

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
        in_array($status, ['accepted', 'rejected']) ?? throw new \Exception("Invalid status", 400);
        $visa_request = VisaRequest::with(['user', 'visa'])
            ->findOrFail($id)
            ->append(['shop_name']);

        $visa_request->update(['status' => $status]);
        return $visa_request;
    }
}
