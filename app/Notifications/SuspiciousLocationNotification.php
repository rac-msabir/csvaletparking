<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use App\Models\SuspiciousLogin;
use App\Channels\WhatsAppChannel;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\Log;

class SuspiciousLocationNotification extends Notification
{
    use Queueable;

    protected $suspiciousLogin;

    /**
     * Create a new notification instance.
     */
    public function __construct(SuspiciousLogin $suspiciousLogin)
    {
        $this->suspiciousLogin = $suspiciousLogin;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return [WhatsAppChannel::class, 'database'];
    }

    /**
     * Get the WhatsApp representation of the notification.
     */
    public function toWhatsApp($notifiable)
    {
        $user = $this->suspiciousLogin->user;
        $distance = number_format($this->suspiciousLogin->distance_km, 2);
        $mapUrl = "https://www.google.com/maps?q={$this->suspiciousLogin->login_latitude},{$this->suspiciousLogin->login_longitude}";
        
        return "🚨 *Suspicious Ticket Creation Alert* 🚨\n\n" .
               "A ticket was created from a suspicious location.\n\n" .
               "*User:* {$user->name}\n" .
               "*Email:* {$user->email}\n" .
               "*Distance from allowed location:* {$distance} km\n" .
               "*Location:* {$this->suspiciousLogin->login_latitude}, {$this->suspiciousLogin->login_longitude}\n" .
               "*Map:* {$mapUrl}\n" .
               "*IP Address:* {$this->suspiciousLogin->ip_address}\n\n" .
               "Please review this activity immediately.";
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'user_id' => $this->suspiciousLogin->user_id,
            'login_latitude' => $this->suspiciousLogin->login_latitude,
            'login_longitude' => $this->suspiciousLogin->login_longitude,
            'distance_km' => $this->suspiciousLogin->distance_km,
            'ip_address' => $this->suspiciousLogin->ip_address,
            'user_agent' => $this->suspiciousLogin->user_agent,
            'created_at' => now()->toDateTimeString(),
        ];
    }
}