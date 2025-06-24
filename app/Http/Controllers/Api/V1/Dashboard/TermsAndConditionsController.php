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
            $data = TermsAndConditions::where('locale', '=', app()->getLocale())->first();
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
                'content' => 'required|string',
            ]);

            $terms = TermsAndConditions::updateOrCreate([
                'locale' => app()->getLocale()
            ], [
                'locale' => app()->getLocale(),
                'content' => $data['content']
            ]);

            return $this->showResponse($terms, 'terms.update_success');
        } catch (\Exception $e) {
            return $this->showError($e, 'terms.errors.update_error');
        }
    }
}
