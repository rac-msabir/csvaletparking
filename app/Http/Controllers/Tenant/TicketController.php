<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Illuminate\Support\Str;

class TicketController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tickets = Ticket::where('tenant_id', Auth::user()->tenant_id)
            // ->with(['createdBy', 'assignedTo'])
            ->latest()
            ->paginate(10);

        return Inertia::render('Tenant/Tickets/Index', [
            'tickets' => $tickets,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $employees = User::where('tenant_id', Auth::user()->tenant_id)
            ->where('is_employee', true)
            ->get(['id', 'name']);

        return Inertia::render('Tenant/Tickets/Create', [
            'employees' => $employees,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'required|string|max:20',
            'customer_email' => 'nullable|email',
            'vehicle_make' => 'required|string|max:255',
            'vehicle_model' => 'required|string|max:255',
            'vehicle_color' => 'required|string|max:50',
            'license_plate' => 'nullable|string|max:20',
            'parking_spot' => 'nullable|string|max:50',
            'parking_zone' => 'nullable|string|max:50',
            'special_instructions' => 'nullable|string',
            'assigned_to' => 'nullable|exists:users,id',
        ]);

        $ticket = Ticket::create([
            'uuid' => (string) Str::uuid(),
            'tenant_id' => Auth::user()->tenant_id,
            'ticket_number' => 'TKT-' . strtoupper(Str::random(8)),
            'status' => 'pending',
            'customer_name' => $validated['customer_name'],
            'customer_phone' => $validated['customer_phone'],
            'customer_email' => $validated['customer_email'] ?? null,
            'vehicle_make' => $validated['vehicle_make'],
            'vehicle_model' => $validated['vehicle_model'],
            'vehicle_color' => $validated['vehicle_color'],
            'license_plate' => $validated['license_plate'] ?? null,
            'parking_spot' => $validated['parking_spot'] ?? null,
            'parking_zone' => $validated['parking_zone'] ?? null,
            'special_instructions' => $validated['special_instructions'] ?? null,
            'created_by' => Auth::id(),
            'assigned_to' => $validated['assigned_to'] ?? null,
            'check_in_at' => now(),
        ]);

        return redirect()->route('tenant.tickets.index')
            ->with('success', 'Ticket created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Ticket $ticket)
    {
        return Inertia::render('Tenant/Tickets/Show', [
            'ticket' => $ticket->load(['activities' => function($query) {
                $query->latest();
            }]),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Ticket $ticket)
    {
        $this->authorize('update', $ticket);
        
        $employees = User::where('tenant_id', Auth::user()->tenant_id)
            ->where('is_employee', true)
            ->get(['id', 'name']);

        return Inertia::render('Tenant/Tickets/Edit', [
            'ticket' => $ticket,
            'employees' => $employees,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Ticket $ticket)
    {
        $this->authorize('update', $ticket);

        $validated = $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'required|string|max:20',
            'customer_email' => 'nullable|email',
            'vehicle_make' => 'required|string|max:255',
            'vehicle_model' => 'required|string|max:255',
            'vehicle_color' => 'required|string|max:50',
            'license_plate' => 'nullable|string|max:20',
            'parking_spot' => 'nullable|string|max:50',
            'parking_zone' => 'nullable|string|max:50',
            'special_instructions' => 'nullable|string',
            'assigned_to' => 'nullable|exists:users,id',
            'status' => 'required|in:pending,in_progress,ready,delivered,cancelled',
        ]);

        $ticket->update([
            'customer_name' => $validated['customer_name'],
            'customer_phone' => $validated['customer_phone'],
            'customer_email' => $validated['customer_email'] ?? null,
            'vehicle_make' => $validated['vehicle_make'],
            'vehicle_model' => $validated['vehicle_model'],
            'vehicle_color' => $validated['vehicle_color'],
            'license_plate' => $validated['license_plate'] ?? null,
            'parking_spot' => $validated['parking_spot'] ?? null,
            'parking_zone' => $validated['parking_zone'] ?? null,
            'special_instructions' => $validated['special_instructions'] ?? null,
            'assigned_to' => $validated['assigned_to'] ?? null,
            'status' => $validated['status'],
        ]);

        return redirect()->route('tenant.tickets.index')
            ->with('success', 'Ticket updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Ticket $ticket)
    {
        $this->authorize('delete', $ticket);
        
        $ticket->delete();

        return redirect()->route('tenant.tickets.index')
            ->with('success', 'Ticket deleted successfully.');
    }

    /**
     * Update the status of the specified ticket.
     */
    public function updateStatus(Request $request, Ticket $ticket)
    {
        $this->authorize('update', $ticket);

        $validated = $request->validate([
            'status' => 'required|in:pending,in_progress,ready,delivered,cancelled',
        ]);

        $ticket->update([
            'status' => $validated['status'],
        ]);

        return response()->json(['success' => true]);
    }
}
