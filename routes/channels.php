<?php

use App\Models\Ticket;
use App\Models\User;
use Illuminate\Support\Facades\Broadcast;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

// User private channel
Broadcast::channel('App.Models.User.{id}', function (User $user, $id) {
    return (int) $user->id === (int) $id;
});

// Ticket private channel
Broadcast::channel('ticket.{ticketId}', function (User $user, $ticketId) {
    $ticket = Ticket::findOrFail($ticketId);
    return $user->id === $ticket->created_by || $user->hasRole('admin');
});

// User private channel for Pusher
Broadcast::channel('user.{userId}', function (User $user, $userId) {
    return (int) $user->id === (int) $userId;
});

// Organization private channel
Broadcast::channel('organization.{organizationId}', function (User $user, $organizationId) {
    return (int) $user->tenant_id === (int) $organizationId;
});

// User notifications channel
Broadcast::channel('user-notifications.{userId}', function (User $user, $userId) {
    return (int) $user->id === (int) $userId;
});

// Tenant employees channel
Broadcast::channel('tenant.{tenantId}', function (User $user, $tenantId) {
    return (int) $user->tenant_id === (int) $tenantId && $user->is_employee;
});
