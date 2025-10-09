<?php

namespace App\Events;

use App\Models\Ticket;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Route;

class VehicleRequested implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $ticket;

    /**
     * Create a new event instance.
     */
    public function __construct(Ticket $ticket)
    {
        $this->ticket = $ticket->load(['creator', 'tenant']);
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel|string>
     */
    public function broadcastOn(): array
    {
        $channels = [
            new PrivateChannel('ticket.' . $this->ticket->id),
            new PrivateChannel('user.' . $this->ticket->created_by),
        ];

        // If you need to broadcast to tenant admins, uncomment and modify this:
        // if ($this->ticket->tenant_id) {
        //     $channels[] = new PrivateChannel('tenant.' . $this->ticket->tenant_id);
        // }

        return $channels;
    }

    /**
     * The event's broadcast name.
     */
    public function broadcastAs(): string
    {
        return 'vehicle.requested';
    }

    /**
     * Get the data to broadcast.
     *
     * @return array<string, mixed>
     */
    public function broadcastWith(): array
    {
        return [
            'ticket_id' => $this->ticket->id,
            'status' => $this->ticket->status,
            'requested_at' => now()->toDateTimeString(),
            'message' => 'A vehicle has been requested for ticket #' . $this->ticket->id,
            'url' => route('employee.tickets.show', $this->ticket->id),
        ];
    }
}
