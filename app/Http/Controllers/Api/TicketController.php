<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TicketController extends Controller
{
    /**
     * Handle a vehicle request.
     */
    public function requestVehicle(Ticket $ticket)
    {
        // Update the ticket status to 'in_progress'
        $ticket->update([
            'status' => 'in_progress',
        ]);

        // Dispatch the VehicleRequested event
        \App\Events\VehicleRequested::dispatch($ticket);

        // Notify the employee who created the ticket
        if ($ticket->creator) {
            $ticket->creator->notify(new \App\Notifications\VehicleRequestedNotification($ticket));
        }

        // Log the activity
        activity()
            ->performedOn($ticket)
            ->causedBy(auth()->user() ?? $ticket->creator)
            ->log('Vehicle requested for ticket #' . $ticket->id);

        return response()->json([
            'message' => 'Vehicle request received. Your car is being prepared.',
            'ticket' => $ticket->fresh()
        ]);
    }
}
