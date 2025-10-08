<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TicketController extends Controller
{
    /**
     * Display the specified ticket.
     */
    public function show(string $token)
    {
        $ticket = Ticket::where('public_token', $token)->firstOrFail();
        
        return Inertia::render('Public/Ticket/Show', [
            'ticket' => $ticket->only([
                'id',
                'ticket_number',
                'status',
                'customer_name',
                'customer_phone',
                'vehicle_make',
                'vehicle_model',
                'vehicle_color',
                'license_plate',
                'parking_spot',
                'check_in_at',
                'public_url',
                'check_in_latitude',
                'check_in_longitude'
            ]),
        ]);
    }
}
