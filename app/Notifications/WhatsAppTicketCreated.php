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
        $message = "🚗 *Valet Parking Confirmation* 🚗\n\n";
        $message .= "Thank you for using our valet service!\n\n";
        $message .= "*Ticket #*: {$this->ticket->ticket_number}\n";
        $message .= "*Vehicle*: {$this->ticket->vehicle_make} {$this->ticket->vehicle_model}\n";
        $message .= "*Color*: {$this->ticket->vehicle_color}\n";
        $message .= $this->ticket->license_plate ? "*Plate*: {$this->ticket->license_plate}\n" : "";
        $message .= "*Location*: {$this->ticket->parking_zone}\n";
        $message .= "*Status*: " . ucfirst(str_replace('_', ' ', $this->ticket->status)) . "\n\n";
        $message .= "🔗 *Track your vehicle status here*:\n";
        $message .= "{$this->ticket->public_url}\n\n";
        $message .= "Please keep this information safe. You can use the link above to check your vehicle status at any time.";

        return $message;
    }
}
