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
        $createdAt = $this->ticket->created_at->timezone('Asia/Riyadh')->format('Y-m-d h:i A');
        
        $message = "*New ticket/تذكرة جديدة*\n\n";
        $message .= "حياك الله مع نوا - شركة الرقي المتطورة لخدمة صف السيارات\n";
        $message .= "تقدر تطلع على التفاصيل وتطلب سيارتك باستخدام الرابط\n\n";
        $message .= "Welcome to Nua - Advanced Prestige valet services\n";
        $message .= "You may checkout the details and request your car via this link\n\n";
        $message .= "رقم التذكرة (Ticket No)\n";
        $message .= "{$this->ticket->ticket_number}\n";
        $message .= "{$createdAt}\n";
        $message .= $this->ticket->public_url;

        return $message;
    }
}
