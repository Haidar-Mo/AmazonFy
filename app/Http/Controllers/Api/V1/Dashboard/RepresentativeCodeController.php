<?php

namespace App\Http\Controllers\Api\V1\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\RepresentativeCode;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;

class RepresentativeCodeController extends Controller
{

    use ResponseTrait;


    public function index()
    {
        try {
            $codes = RepresentativeCode::all();
            return $this->showResponse($codes);

        } catch (\Exception $e) {
            return $this->showError($e);
        }
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'code' => 'required',
            'representor_name' => 'nullable'
        ]);
        try {
            $code = RepresentativeCode::create($data);
            return $this->showResponse($code);

        } catch (\Exception $e) {
            return $this->showError($e);
        }
    }

    public function destroy(string $id)
    {
        try {
            RepresentativeCode::findOrFail($id)->delete();
            return $this->showMessage('api.success');
        } catch (\Exception $e) {
            return $this->showError($e);
        }
    }

}
