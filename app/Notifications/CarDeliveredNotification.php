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
        return <<<MSG
تذكرة جديدة – فعالية التجزئة الحصرية | مركز مبيعات الدرعية

حياك الله في فعالية التجزئة الحصرية في مركز مبيعات الدرعية.
يمكنك الاطلاع على تفاصيل طلب سيارتك وطلبها عبر الرابط أدناه:

رقم التذكرة: {$this->ticket->ticket_number}
الوقت: {$this->ticket->check_in_at}
الرابط: {$this->ticket->public_url}

New Ticket – Exclusive Retail Event | Diriyah Sales Center
Welcome to Diriyah’s exclusive retail event at the Diriyah Sales Center.
You can view your request details and call your car using the link below:

Ticket No: {$this->ticket->ticket_number}
Date & Time: {$this->ticket->check_in_at}
Link: {$this->ticket->public_url}

⸻
شكراً لانضمامكم إلينا في الدرعية

شكرًا لانضمامكم إلينا في هذا المساء.
للاقتراحات أو الشكاوى بخصوص خدمة صف السيارات يرجى التواصل 0595988851
رافقتكم السلامة.

Thank you for joining us in Diriyah. 

Thank you for joining us this evening.
For valet service suggestions or complaints please contact: 0595988851
We wish you a safe journey.
MSG;
    }
}
