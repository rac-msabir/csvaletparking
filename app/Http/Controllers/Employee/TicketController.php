<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use Inertia\Inertia;
use App\Http\Requests\StoreTicketRequest;
use App\Models\Ticket;
use App\Notifications\WhatsAppTicketCreated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use BaconQrCode\Renderer\Image\SvgImageBackEnd;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;


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
        $validated = $request->validated();
        // Create the ticket
        $ticket = Ticket::create($validated);

        // Handle file uploads
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('ticket-images', 's3'); // or 'public' for local storage

                $ticket->images()->create([
                    'path' => $path,
                    'original_name' => $image->getClientOriginalName(),
                    'mime_type' => $image->getClientMimeType(),
                    'size' => $image->getSize(),
                ]);
            }
        }

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

        // Send WhatsApp notification to customer if phone number is provided
        if ($ticket->customer_phone) {
            try {
                $ticket->notify(new WhatsAppTicketCreated($ticket));
            } catch (\Exception $e) {
                // Log the error but don't fail the request
                \Log::error('Failed to send WhatsApp notification: ' . $e->getMessage());
            }
        }

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

        // Generate unique filename
        $directory = 'qrcodes/tickets';
        $filename = 'ticket_' . $ticket->ticket_number . '_' . time() . '.svg';
        $path = $directory . '/' . $filename;

        // Generate QR code using Bacon QR Code
        $renderer = new ImageRenderer(
            new RendererStyle(300),
            new SvgImageBackEnd()
        );
        $writer = new Writer($renderer);
        $svg = $writer->writeString($qrContent);

        // Store in S3 bucket with public visibility
        Storage::disk('s3')->put($path, $svg, 'public');

        // Get the full public URL to the stored file
        $bucket = config('filesystems.disks.s3.bucket');
        $endpoint = rtrim(config('filesystems.disks.s3.endpoint'), '/');
        
        // Return the full public URL
        return "{$endpoint}/{$bucket}/{$path}";
    }

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
        if ($ticket->assigned_to !== auth()->id()) {
            abort(403, 'You are not authorized to view this ticket.');
        }

        return Inertia::render('Employee/Tickets/Show', [
            'ticket' => $ticket,
        ]);
    }

    public function destroy(Ticket $ticket)
    {
        //
    }

    /**
     * Display a printable version of the ticket.
     */
    public function print(Ticket $ticket)
    {
        // Ensure the ticket is assigned to the current employee
        if ($ticket->assigned_to !== Auth::id()) {
            abort(403, 'You are not authorized to view this ticket.');
        }

        // Generate QR code
        $qrCodeUrl = $this->generateQrCode(route('tickets.public.show', $ticket->token));

        return Inertia::render('Tickets/Print', [
            'ticket' => $ticket->load('images'),
            'qrCodeUrl' => $qrCodeUrl,
            'appName' => config('app.name'),
            'appUrl' => config('app.url')
        ]);
    }

    /**
     * Update the status of the specified ticket.
     */
    public function updateStatus(Request $request, Ticket $ticket)
    {
        $request->validate([
            'status' => 'required|in:pending,ready,delivered',
        ]);

        // Ensure the ticket is assigned to the current employee
        if ($ticket->assigned_to !== Auth::id()) {
            return response()->json([
                'message' => 'You are not authorized to update this ticket.',
            ], 403);
        }

        DB::beginTransaction();
        try {
            $status = $request->status;
            $updates = ['status' => $status];

            // Update timestamps based on status
            if ($status === 'ready') {
                $updates['ready_at'] = now();
            } elseif ($status === 'delivered') {
                $updates['delivered_at'] = now();
            }

            $ticket->update($updates);

            // Log the status update
            activity()
                ->causedBy(Auth::user())
                ->performedOn($ticket)
                ->withProperties([
                    'status' => $status,
                    'ip' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                ])
                ->log('updated ticket status');

            DB::commit();

            return response()->json([
                'message' => 'Ticket status updated successfully',
                'ticket' => $ticket->fresh(),
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating ticket status: ' . $e->getMessage());

            return response()->json([
                'message' => 'Failed to update ticket status',
                'error' => config('app.debug') ? $e->getMessage() : null,
            ], 500);
        }
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
    /**
     * Update the specified ticket in storage.
     */
    public function update(StoreTicketRequest $request, Ticket $ticket)
    {
        // Ensure the ticket is assigned to the current employee
        // if ($ticket->assigned_to !== Auth::id()) {
        //     abort(403);
        // }

        $validated = $request->validated();

        // Update the ticket with validated data
        $ticket->update($validated);

        return redirect()->route('employee.tickets.show', $ticket)
            ->with('success', 'Ticket updated successfully.');
    }

}
