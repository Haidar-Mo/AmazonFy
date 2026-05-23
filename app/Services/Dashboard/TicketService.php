<?php

namespace App\Services\Dashboard;

use App\Models\TicketReservation;
use Illuminate\Http\Request;

/**
 * Class TicketService.
 */
class TicketService
{

    public function list()
    {
        $list = TicketReservation::with(['user', 'airLine'])
            ->get()
            ->append(['shop_name']);
        return $list;
    }

    public function show(string $id)
    {
        $reservation = TicketReservation::with(['user', 'airLine', 'fields'])
            ->findOrFail($id)
            ->append(['shop_name'])
            ->makeVisible(['user', 'airLine']);
        return $reservation;
    }

    public function delete(string $id)
    {
        TicketReservation::findOrFail($id)->delete();
    }

    public function changeStatus(string $id, Request $request)
    {
        $status = $request->input('status');
        in_array($status, ['accepted', 'rejected']) ?? throw new \Exception("Invalid status", 400);
        $reservation = TicketReservation::with(['user', 'airLine'])
            ->findOrFail($id)
            ->append(['shop_name']);

        $reservation->update(['status' => $status]);
        return $reservation;
    }
}
