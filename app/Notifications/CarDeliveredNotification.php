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
تم تسليم سيارتك
شكرا لزيارتكم مركز مبيعات الدرعية،
+966 56 520 6244للاقتراحات والشكاوى على خدمة صف السيارات يرجى الاتصال على الرقم 
رافقتكم السلامة

Your car has been delivered.
Thank you for visiting Diriyah Sales Center.
For suggestions and complaints about the valet services please contact +966 59 940 9500
We wish you a safe journey.
MSG;
    }
}
