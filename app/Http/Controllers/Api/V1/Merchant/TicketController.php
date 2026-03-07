<?php

namespace App\Http\Controllers\Api\V1\Merchant;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Merchant\TicketReservationStoreRequest;
use App\Http\Resources\TicketReservationResource;
use App\Models\AirLine;
use App\Models\TicketReservation;
use App\Traits\HasFiles;
use App\Traits\ResponseTrait;
use DB;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    use ResponseTrait, HasFiles;

    public function index()
    {
        $ticket_reservations = auth()->user()->ticketReservations;
        return $this->showResponse($ticket_reservations);
    }

    /**
     * Store the newly created resource in storage.
     */
    public function store(TicketReservationStoreRequest $request)
    {
        $data = $request->validated();
        try {
            $response_data = DB::transaction(function () use ($data) {

                $airLine = AirLine::with(['requiredFields'])
                    ->findOrFail($data['air_line_id']);

                $reservation = TicketReservation::create([
                    'air_line_id' => $airLine->id,
                    'user_id' => auth()->id(),
                    'status' => 'pending',
                ]);

                $required_field = $airLine->requiredFields->where('is_file', false)->all();
                $required_documents = $airLine->requiredFields->where('is_file', true)->all();

                //: Validate Required Fields
                foreach ($required_field as $field) {

                    $submitted = collect($data['fields'])
                        ->firstWhere('field_id', $field->id);

                    if ($field->is_required && !$submitted) {
                        throw new \Exception("Missing required field: {$field->label}");
                    }

                    if ($submitted) {
                        $reservation->fields()->create([
                            'air_line_required_field_id' => $field->id,
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
                        $path = $this->saveFile($submitted['file'], 'ticket-documents');
                    }
                    $reservation->fields()->create([
                        'air_line_required_field_id' => $document->id,
                        'value' => $path,
                    ]);
                }

                return $reservation->load(['fields']);
            });

            return $this->showResponse(new TicketReservationResource($response_data), 'ticket.reservation.submission_success');
        } catch (\Exception $e) {
            return $this->showError($e, 'ticket.reservation.errors.submission_error');
        }
    }

    /**
     * Display the resource.
     */
    public function show(string $id)
    {
        try {
            $ticket_reservation = auth()->user()->ticketReservations()
                ->with(['fields'])
                ->findOrFail($id);
            return $this->showResponse(new TicketReservationResource($ticket_reservation));
        } catch (\Exception $e) {
            return $this->showError($e);
        }
    }

}
