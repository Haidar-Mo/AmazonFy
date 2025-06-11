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
                    'arabic_content' => __('terms.arabic_placeholder'),
                    'english_content' => __('terms.english_placeholder'),
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
                'english_content' => 'sometimes|string',
                'arabic_content' => 'sometimes|string'
            ]);

            $terms = TermsAndConditions::first();
            if ($terms) {
                $terms->update($data);
            } else {
                TermsAndConditions::create($data);
            }

            return $this->showResponse(TermsAndConditions::first(), 'terms.update_success');
        } catch (\Exception $e) {
            return $this->showError($e, 'terms.errors.update_error');
        }
    }
}
