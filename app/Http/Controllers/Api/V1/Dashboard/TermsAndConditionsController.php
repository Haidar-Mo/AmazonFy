<?php

namespace App\Http\Controllers\Api\V1\dashboard;

use App\Http\Controllers\Controller;
use App\Models\TermsAndConditions;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;

class TermsAndConditionsController extends Controller
{
    use ResponseTrait;


    public function show()
    {
        return $this->showResponse(TermsAndConditions::firstOrNew(['content' => 'HERE WILL BE THE TERMS AND CONDITIONS']), 'تم عرض الشروط و الأحكام بنجاح');
    }

    public function update(Request $request)
    {
        $data = $request->validate(['content' => 'required|string']);
        TermsAndConditions::firstOrCreate($data);
        return $this->showResponse(TermsAndConditions::first(), 'تم تعديل الشروط و الأحكام بنجاح');

    }
}
