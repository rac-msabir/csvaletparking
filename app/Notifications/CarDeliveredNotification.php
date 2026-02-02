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
