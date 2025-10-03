<?php

namespace App\Policies;

use App\Models\Ticket;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class TicketPolicy
{
    /**
     * Determine whether the user can update the ticket.
     */
    public function update(User $user, Ticket $ticket): bool
    {
        // Allow update if the user belongs to the same tenant as the ticket
        return $user->tenant_id === $ticket->tenant_id;
    }
}
