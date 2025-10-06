<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class TicketController extends Controller
{
    /**
     * Display a listing of the tickets assigned to the employee.
     */
    public function index()
    {
        $tickets = Ticket::where('assigned_to', Auth::id())
            ->with(['createdBy', 'assignedTo'])
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

        $ticket->load(['createdBy', 'assignedTo', 'images']);
        
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
        if ($ticket->assigned_to !== Auth::id()) {
            abort(403);
        }

        return Inertia::render('Employee/Tickets/Edit', [
            'ticket' => $ticket->load(['createdBy', 'assignedTo']),
        ]);
    }

    /**
     * Update the specified ticket in storage.
     */
    public function update(Request $request, Ticket $ticket)
    {
        // Ensure the ticket is assigned to the current employee
        if ($ticket->assigned_to !== Auth::id()) {
            abort(403);
        }

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
