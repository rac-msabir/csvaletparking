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
شكرا لزيارتك مكتب مبيعات الدرعية واستخدامك خدمة شركة التحفة اللامعة لصف السيارات ، رافقتك السلامة

Your car has been delivered 
Thanks for visiting Sales center Diriyah and using CS Valet Parking, we wish you a safe journey
MSG;
}
}
