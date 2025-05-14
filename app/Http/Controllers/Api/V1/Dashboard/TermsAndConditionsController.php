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
        return $this->showResponse(TermsAndConditions::first(), 'تم عرض الشروط و الأحكام بنجاح');
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'english_content' => 'sometimes|string',
            'arabic_content' => 'sometimes|string'
        ]);
        $terms = TermsAndConditions::first();
        if ($terms) {
            $terms->update($data);
        } else {
            TermsAndConditions::create($data);
        }
        return $this->showResponse(TermsAndConditions::first(), 'تم تعديل الشروط و الأحكام بنجاح');

    }
}
