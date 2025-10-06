<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use Inertia\Inertia;
use App\Http\Requests\StoreTicketRequest;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use App\Models\Employee;

class TicketController extends Controller
{
    /**
     * Show the form for creating a new ticket.
     */
    public function create()
    {
        return Inertia::render('Employee/Tickets/Create', [
            'employees' => Auth::user()->employees,
        ]);
    }

    /**
     * Store a newly created ticket in storage.
     */
    public function store(StoreTicketRequest $request)
    {
        $ticket = Ticket::create($request->validated());

        // Generate ticket number if not provided
        if (empty($ticket->ticket_number)) {
            $ticket->update([
                'ticket_number' => 'TKT-' . str_pad($ticket->id, 6, '0', STR_PAD_LEFT)
            ]);
        }

        // TODO: Generate and save QR code
        // $ticket->update([
        //     'qr_code_path' => $this->generateQrCode($ticket)
        // ]);

        // Return the created ticket data for Inertia
        return Inertia::render('Employee/Tickets/Create', [
            'ticket' => $ticket->fresh(),
            'success' => 'Ticket created successfully.'
        ]);
    }

    /**
     * Generate QR code for the ticket
     */
    // protected function generateQrCode($ticket)
    // {
    //     $qrCode = QrCode::size(200)
    //         ->generate(route('tickets.show', $ticket->uuid));
    //
    //     $path = 'qrcodes/' . $ticket->uuid . '.svg';
    //    
    //     Storage::disk('public')->put($path, $qrCode);
    //
    //     return $path;
    // }
    /**
     * Display a listing of the tickets assigned to the employee.
     */
    public function index()
    {
        $tickets = Ticket::where('assigned_to', Auth::id())
            ->paginate(10);

        return Inertia::render('Employee/Tickets/Index', [
            'tickets' => $tickets,
        ]);
    }

    
    /**
     * Display the specified ticket.
     */
    public function show(Ticket $ticket)
    {
        // Ensure the ticket is assigned to the current employee
        if ($ticket->assigned_to !== Auth::id()) {
            abort(403);
        }

        $ticket->load(['images']);
        
        return Inertia::render('Employee/Tickets/Show', [
            'ticket' => $ticket,
        ]);
    }

    /**
     * Show the form for editing the specified ticket.
     */
    public function edit(Ticket $ticket)
    {
        // Ensure the ticket is assigned to the current employee
        // if ($ticket->assigned_to !== Auth::id()) {
        //     abort(403);
        // }

        return Inertia::render('Employee/Tickets/Edit', [
            'ticket' => $ticket,
        ]);
    }

    /**
     * Update the specified ticket in storage.
     */
    public function update(Request $request, Ticket $ticket)
    {
        // Ensure the ticket is assigned to the current employee
        // if ($ticket->assigned_to !== Auth::id()) {
        //     abort(403);
        // }

        $validated = $request->validate([
            'status' => 'required|in:pending,open,in_progress,completed,cancelled',
            'notes' => 'nullable|string',
        ]);

        // Update the ticket status
        $ticket->update([
            'status' => $validated['status'],
            'notes' => $validated['notes'] ?? $ticket->notes,
            'completed_at' => $validated['status'] === 'completed' ? now() : null,
        ]);

        return redirect()->route('employee.tickets.index')
            ->with('success', 'Ticket updated successfully.');
    }

}
