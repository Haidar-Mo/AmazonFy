<?php

namespace App\Http\Controllers\Api\V1\Merchant;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Merchant\VisaArrangementStoreRequest;
use App\Http\Resources\VisaArrangementResource;
use App\Models\Visa;
use App\Models\VisaArrangement;
use App\Traits\HasFiles;
use App\Traits\ResponseTrait;
use DB;
use Illuminate\Http\Request;

class VisaArrangementController extends Controller
{
    use ResponseTrait;
    use HasFiles;


    public function index()
    {
        $visas_requests = auth()->user()->visaArrangements;
        return $this->showResponse($visas_requests);
    }

    /**
     * Send an Visa Arrangement Request for a specific Visa Request.
     * @param string $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(VisaArrangementStoreRequest $request)
    {
        $data = $request->validated();
        try {
            $response_data = DB::transaction(function () use ($data) {

                $visa = Visa::with(['requiredFields'])
                    ->findOrFail($data['visa_id']);

                $request = VisaArrangement::create([
                    'visa_id' => $visa->id,
                    'user_id' => auth()->id(),
                    'status' => 'pending',
                ]);

                $required_field = $visa->requiredFields->where('type', '!=', 'file')->all();
                $required_documents = $visa->requiredFields->where('type', 'file')->all();

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
                        $path = $this->saveFile($submitted['file'], 'visa-arrangement');
                    }
                    $request->fields()->create([
                        'visa_required_field_id' => $document->id,
                        'value' => $path,
                    ]);
                }

                return $request->load(['fields']);
            });

            return $this->showResponse(new VisaArrangementResource($response_data));
        } catch (\Exception $e) {
            return $this->showError($e, 'visa.request.errors.submission_error');
        }
    }


    public function show(string $id)
    {
        try {
            $visa_request = auth()->user()->visaArrangements()
                ->with(['fields'])
                ->findOrFail($id);
            return $this->showResponse(new VisaArrangementResource($visa_request));
        } catch (\Exception $e) {
            return $this->showError($e);
        }
    }

}
