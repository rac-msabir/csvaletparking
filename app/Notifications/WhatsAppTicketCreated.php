<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class WhatsAppTicketCreated extends Notification implements ShouldQueue
{
    use Queueable;

    protected $ticket;

    public function __construct($ticket)
    {
        $this->ticket = $ticket;
    }

    public function via($notifiable)
    {
        return ['whatsapp'];
    }

    public function toWhatsApp($notifiable)
    {
        $message = "Thank you for using our valet service!\n";
        $message .= "Ticket #: {$this->ticket->ticket_number}\n";
        $message .= "Vehicle: {$this->ticket->vehicle_make} {$this->ticket->vehicle_model} {$this->ticket->license_plate}\n";
        $message .= "Location: {$this->ticket->parking_zone}\n";
        $message .= "Please keep this ticket number for vehicle collection.";

        return $message;
    }
}
