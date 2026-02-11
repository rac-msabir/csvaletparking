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
New ticket/تذكرة جديدة

حياك الله  في مركز مبيعات شركة الدرعية. بإمكانك الاطلاع على طلبك باستخدام الرابط أدناه. 
Welcome to Diriyah Sales Center.
You may checkout the details and request your car via this link
رقم التذكرة (Ticket No) 
{$this->ticket->ticket_number}
{$createdAt}
{$this->ticket->public_url}
MSG;
    }
}
