<?php

namespace App\Http\Controllers\Api\V1\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\TermsAndConditions;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;

class TermsAndConditionsController extends Controller
{
    use ResponseTrait;

    public function show()
    {
        try {
            $data = TermsAndConditions::first();
            if (!$data) {
                $data = [
                    'content' => __('texts.terms'),
                ];
            }
            return $this->showResponse($data, 'terms.show_success');
        } catch (\Exception $e) {
            return $this->showError($e, 'terms.errors.show_error');
        }
    }


    public function update(Request $request)
    {
        try {
            $data = $request->validate([
                'content_ar' => 'required_without:content_en|string',
                'content_en' => 'required_without:content_ar|string',
            ]);
            if ($terms = TermsAndConditions::first()) {
                if (isset($data['content_ar'])) {
                    $terms->translateOrNew('ar')->content = $data['content_ar'];
                }
                if (isset($data['content_en'])) {
                    $terms->translateOrNew('en')->content = $data['content_en'];
                }
            } else {
                $terms = TermsAndConditions::create();
                $terms->translations()->createMany([
                    [
                        'local' => 'en',
                        'content' => $data['content_en']
                    ],
                    [
                        'local' => 'ar',
                        'content' => $data['content_ar']
                    ]
                ]);
            }

            return $this->showResponse($terms, 'terms.update_success');
        } catch (\Exception $e) {
            return $this->showError($e, 'terms.errors.update_error');
        }
    }
}
