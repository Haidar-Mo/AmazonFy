<?php

namespace App\Http\Controllers\Api\V1\Merchant;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Merchant\StoreVisaRequestRequest;
use App\Http\Resources\VisaRequestResource;
use App\Models\Visa;
use App\Models\VisaRequest;
use App\Traits\HasFiles;
use App\Traits\ResponseTrait;
use DB;
use Illuminate\Http\Request;

class VisaRequestController extends Controller
{
    use ResponseTrait, HasFiles;

    public function index()
    {
        $visas_requests = auth()->user()->visaRequest;
        return $this->showResponse($visas_requests);
    }

    /**
     * Store the newly created resource in storage.
     */
    public function store(StoreVisaRequestRequest $request)
    {
        $data = $request->validated();
        try {
            $response_data = DB::transaction(function () use ($data) {

                $visa = Visa::with(['requiredFields'])
                    ->findOrFail($data['visa_id']);

                $request = VisaRequest::create([
                    'visa_id' => $visa->id,
                    'user_id' => auth()->id(),
                    'status' => 'pending',
                ]);

                $required_field = $visa->requiredFields->where('is_file', false)->all();
                $required_documents = $visa->requiredFields->where('is_file', true)->all();

                //: Validate Required Fields
                foreach ($required_field as $field) {

                    $submitted = collect($data['fields'])
                        ->firstWhere('field_id', $field->id);

                    if ($field->is_required && !$submitted) {
                        throw new \Exception("Missing required field: {$field->label}");
                    }

                    if ($submitted) {
                        $request->fields()->create([
                            'visa_required_field_id' => $field->id,
                            'value' => $submitted['value'],
                        ]);
                    }
                }

                //: Attach Required Documents
                foreach ($required_documents as $document) {

                    $submitted = collect($data['documents'])
                        ->firstWhere('document_id', $document->id);

                    if ($document->is_required && !$submitted) {
                        throw new \Exception("Missing required File: {$document->label}");
                    }
                    $path = null;
                    if ($submitted['file'] instanceof \Illuminate\Http\UploadedFile) {
                        $path = $this->saveFile($submitted['file'], 'visa-documents');
                    }
                    $request->fields()->create([
                        'visa_required_field_id' => $document->id,
                        'value' => $path,
                    ]);
                }

                return $request->load(['fields']);
            });

            return $this->showResponse(new VisaRequestResource($response_data), 'visa.request.submission_success');
            // return $this->showResponse($response_data, 'visa.request.submission_success');
        } catch (\Exception $e) {
            return $this->showError($e, 'visa.request.errors.submission_error');
        }
    }

    /**
     * Display the resource.
     */
    public function show(string $id)
    {
        try {
            $visa_request = auth()->user()->visaRequest()
                ->with(['fields'])
                ->findOrFail($id);
            return $this->showResponse(new VisaRequestResource($visa_request));
        } catch (\Exception $e) {
            return $this->showError($e);
        }
    }

    //DO:?  create a postman API  => How to return mony to the merchant ??

    /* public function destroy(string $id)
    {
        try {
            $visa_request = auth()->user()->visaRequest()
                ->where('status', 'pending')
                ->where('id', $id)
                ->firstOrFail();
            $visa_request->delete();
            return $this->showMessage('visa.request.delete_success');
        } catch (\Exception $e) {
            return $this->showError($e, 'visa.request.errors.delete_error');
        }
    } */
}
