<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTicketRequest;
use App\Models\Ticket;
use App\Notifications\WhatsAppTicketCreated;
use App\Notifications\CarReadyNotification;
use App\Notifications\CarDeliveredNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use BaconQrCode\Renderer\Image\SvgImageBackEnd;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;

class TicketController extends Controller
{
    /**
     * Display a listing of the tickets assigned to the employee.
     */
    public function index()
    {
        $tickets = Ticket::where('assigned_to', Auth::id())
            ->paginate(10);

        return Inertia::render('Tenant/Tickets/Index', [
            'tickets' => $tickets,
        ]);
    }

    /**
     * Show the form for creating a new ticket.
     */
    public function create()
    {
        return Inertia::render('Tenant/Tickets/Create', [
            'employees' => Auth::user()->employees,
        ]);
    }

    /**
     * Store a newly created ticket in storage.
     */
    // In your TicketController
    public function store(StoreTicketRequest $request)
    {
        $user = Auth::user();
        $validated = $request->validated();
        
        // If user is a tenant admin, use their user_id as tenant_id
        if ($user->is_tenant_admin) {
            $validated['tenant_id'] = $user->id;
        }
        
        $ticket = Ticket::create($validated);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                try {
                    $directory = 'ticket-images';
                    $filename = 'ticket_' . $ticket->id . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                    $path = $directory . '/' . $filename;

                    // Store in S3 bucket with public visibility
                    Storage::disk('s3')->put($path, file_get_contents($image), 'public');

                    // Get the S3 client
                    $s3Client = new \Aws\S3\S3Client([
                        'version' => 'latest',
                        'region' => config('filesystems.disks.s3.region'),
                        'credentials' => [
                            'key' => config('filesystems.disks.s3.key'),
                            'secret' => config('filesystems.disks.s3.secret'),
                        ],
                        'endpoint' => config('filesystems.disks.s3.endpoint'),
                        'use_path_style_endpoint' => true,
                    ]);

                    // Create a command to get the object
                    $command = $s3Client->getCommand('GetObject', [
                        'Bucket' => config('filesystems.disks.s3.bucket'),
                        'Key' => $path
                    ]);

                    // Create a signed URL that's valid for 1 year
                    $signedUrl = (string) $s3Client->createPresignedRequest(
                        $command,
                        '+7 days'  // Maximum allowed by AWS S3
                    )->getUri();


                    $ticket->images()->create([
                        'path' => $path,
                        'url' => $signedUrl,  // Store the signed URL
                        'original_name' => $image->getClientOriginalName(),
                        'mime_type' => $image->getClientMimeType(),
                        'size' => $image->getSize(),
                        'uploaded_by' => auth()->id(),
                    ]);
                } catch (\Exception $e) {
                    \Log::error('Error uploading image: ' . $e->getMessage());
                    throw $e; // Re-throw to see the full error in development
                }


                // Rest of your code (QR code generation, etc.)
                if (empty($ticket->ticket_number)) {
                    $ticket->update([
                        'ticket_number' => 'TKT-' . str_pad($ticket->id, 6, '0', STR_PAD_LEFT)
                    ]);
                }

                $qrCodePath = $this->generateQrCode($ticket);
                $ticket->update([
                    'qr_code_path' => $qrCodePath
                ]);

                if ($ticket->customer_phone) {
                    try {
                        $ticket->notify(new WhatsAppTicketCreated($ticket));
                    } catch (\Exception $e) {
                        \Log::error('Failed to send WhatsApp notification: ' . $e->getMessage());
                    }
                }

                return Inertia::render('Tenant/Tickets/Create', [
                    'ticket' => $ticket->fresh(),
                    'success' => 'Ticket created successfully.'
                ]);
            }
        }
    }

    protected function generateQrCode($ticket)
    {
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

        $directory = 'qrcodes/tickets';
        $filename = 'ticket_' . $ticket->ticket_number . '_' . time() . '.svg';
        $path = $directory . '/' . $filename;

        $renderer = new ImageRenderer(
            new RendererStyle(300),
            new SvgImageBackEnd()
        );
        $writer = new Writer($renderer);
        $svg = $writer->writeString($qrContent);

        // Store in S3 bucket with public visibility
        Storage::disk('s3')->put($path, $svg, 'public');

        // Get the full public URL to the stored file
        // For Cloudflare R2, we'll construct the URL manually since the url() method might not work as expected
        $bucket = config('filesystems.disks.s3.bucket');
        $endpoint = rtrim(config('filesystems.disks.s3.endpoint'), '/');
        
        // Return the full public URL
        return "{$endpoint}/{$bucket}/{$path}";
    }

    /**
     * Display the specified ticket.
     */
    // In your TicketController's show and edit methods
    public function show(Ticket $ticket)
    {
        $ticket->load('images');
        return Inertia::render('Tenant/Tickets/Show', [
            'ticket' => $ticket
        ]);
    }

    public function edit(Ticket $ticket)
    {
        $ticket->load('images');
        return Inertia::render('Tenant/Tickets/Edit', [
            'ticket' => $ticket
        ]);
    }

    /**
     * Update the specified ticket in storage.
     */
    public function update(StoreTicketRequest $request, Ticket $ticket)
    {
        // Ensure the ticket is assigned to the current employee
        // if ($ticket->assigned_to !== Auth::id()) {
        //     abort(403);
        // }

       $user = Auth::user();
        $validated = $request->validated();

        // If user is a tenant admin, use their user_id as tenant_id
        if ($user->is_tenant_admin) {
            $validated['tenant_id'] = $user->id;
        }

        // Update the ticket with validated data
        $ticket->update($validated);

        return redirect()->route('tenant.tickets.show', $ticket)
            ->with('success', 'Ticket updated successfully.');
    }

    public function destroy(Ticket $ticket)
    {
        //
    }

    /**
     * Update the status of the specified ticket.
     */
    public function updateStatus(Request $request, Ticket $ticket)
    {
        $request->validate([
            'status' => 'required|in:pending,ready,delivered',
        ]);

        if ($ticket->assigned_to !== Auth::id()) {
            return response()->json([
                'message' => 'You are not authorized to update this ticket.',
            ], 403);
        }

        DB::beginTransaction();
        try {
            $status = $request->status;
            $updates = ['status' => $status];

            if ($status === 'ready') {
                $updates['ready_at'] = now();
            } elseif ($status === 'delivered') {
                $updates['delivered_at'] = now();
            }

            $ticket->update($updates);

            // Send notifications based on status change
            if ($ticket->customer_phone) {
                try {
                    if ($status === 'ready') {
                        $ticket->notify(new CarReadyNotification($ticket));
                    } elseif ($status === 'delivered') {
                        $ticket->notify(new CarDeliveredNotification($ticket));
                    }
                } catch (\Exception $e) {
                    Log::error('Failed to send notification: ' . $e->getMessage());
                }
            }

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
     * Display a printable version of the ticket.
     */
    public function print(Ticket $ticket)
    {
        if ($ticket->assigned_to !== Auth::id()) {
            abort(403, 'You are not authorized to view this ticket.');
        }

        return Inertia::render('Tenant/Tickets/Print', [
            'ticket' => $ticket,
            'qrCodeUrl' => $ticket->qr_code_path ? Storage::url($ticket->qr_code_path) : null,
            'appName' => config('app.name'),
            'appUrl' => config('app.url')
        ]);
    }

}
