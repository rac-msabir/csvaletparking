<?php

namespace App\Notifications;

use App\Models\Ticket;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class CarDeliveredNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $ticket;

    public function __construct(Ticket $ticket)
    {
        $this->ticket = $ticket;
    }

    public function via($notifiable)
    {
        return ['whatsapp'];
    }

    public function toWhatsApp($notifiable)
    {
        $tenantPhone = $this->ticket->tenant->phone ?? '0547277708';
    
        $message = "شكرا لزيارتك نوا واستخدامك خدمة شركة الرقي المتطورة ، للشكاوى على خدمة صف السيارات يرجى الاتصال على الرقم\t{$tenantPhone}\n";
        $message .= "رافقتك السلامة";
        $message .= "\n\nThanks for visiting Nua and using Advanced Prestige.\t";
        $message .= "For complaints about valet services, please contact {$tenantPhone}.\t";
        $message .= "We wish you a safe journey!";

        return $message;
    }
}
