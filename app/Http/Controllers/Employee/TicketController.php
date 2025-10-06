<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use Inertia\Inertia;
use Illuminate\Support\Str;
use App\Http\Requests\StoreTicketRequest;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use BaconQrCode\Renderer\Image\SvgImageBackEnd;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;
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

        // Generate and save QR code
        $qrCodePath = $this->generateQrCode($ticket);
        $ticket->update([
            'qr_code_path' => $qrCodePath
        ]);

        // Return the created ticket data for Inertia
        return Inertia::render('Employee/Tickets/Create', [
            'ticket' => $ticket->fresh(),
            'success' => 'Ticket created successfully.'
        ]);
    }

    protected function generateQrCode($ticket)
    {
        // Generate QR code content with consistent format
        $qrContent = json_encode([
            'ticket' => [
                'id' => $ticket->id,
                'ticket_number' => $ticket->ticket_number,
                'customer_name' => $ticket->customer_name,
                'vehicle_make' => $ticket->vehicle_make,
                'vehicle_model' => $ticket->vehicle_model,
                'license_plate' => $ticket->license_plate,
                'created_at' => $ticket->created_at->toIso8601String(),
                'qr_code_path' => $ticket->qr_code_path
            ]
        ]);

        // Create directory if it doesn't exist
        $directory = 'qrcodes/tickets';
        if (!Storage::disk('public')->exists($directory)) {
            Storage::disk('public')->makeDirectory($directory);
        }

        // Generate unique filename
        $filename = 'ticket_' . $ticket->ticket_number . '_' . time() . '.svg';
        $path = $directory . '/' . $filename;
        
        // Generate QR code using Bacon QR Code
        $renderer = new ImageRenderer(
            new RendererStyle(300),
            new SvgImageBackEnd()
        );
        $writer = new Writer($renderer);
        $svg = $writer->writeString($qrContent);
        
        // Save the QR code
        Storage::disk('public')->put($path, $svg);
        
        return $path;
    }

    public function index()
    {
        $tickets = Ticket::where('assigned_to', Auth::id())
            ->with(['assignedEmployee', 'customer'])
            ->latest()
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
