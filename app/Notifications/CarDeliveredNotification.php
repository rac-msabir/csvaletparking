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
شكرا لزيارتكم مركز مبيعات الدرعية
للاقتراحات والشكاوى على خدمة صف السيارات يرجى الاتصال على الرقم 0595988851
رافقتكم السلامة

Thank you for visiting Diriyah Sales Center. For suggestions and complaints about the valet services please contact 0595988851
We wish you a safe journey.
MSG;
    }
}
