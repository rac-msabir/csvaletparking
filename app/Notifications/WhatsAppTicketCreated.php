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

        return <<<MSG
تذكرة جديدة – فعالية التجزئة الحصرية | مركز مبيعات الدرعية

حياك الله في فعالية التجزئة الحصرية في مركز مبيعات الدرعية.
يمكنك الاطلاع على تفاصيل طلب سيارتك وطلبها عبر الرابط أدناه:

رقم التذكرة: {$this->ticket->ticket_number}
الوقت: {$createdAt}
الرابط: {$this->ticket->public_url}

New Ticket – Exclusive Retail Event | Diriyah Sales Center
Welcome to Diriyah’s exclusive retail event at the Diriyah Sales Center.
You can view your request details and call your car using the link below:

Ticket No: {$this->ticket->ticket_number}
Date & Time: {$createdAt}
Link: {$this->ticket->public_url}
MSG;
    }
}
