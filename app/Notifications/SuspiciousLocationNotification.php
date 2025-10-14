<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use App\Models\SuspiciousLogin;
use App\Channels\WhatsAppChannel;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification as NotificationFacade;

class SuspiciousLocationNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $tries = 3; // Number of times to attempt sending the notification
    public $backoff = [5, 15, 30]; // Seconds to wait before retrying (5s, 15s, 30s)

    protected $suspiciousLogin;

    public function __construct(SuspiciousLogin $suspiciousLogin)
    {
        $this->suspiciousLogin = $suspiciousLogin;
    }

    public function via(object $notifiable): array
    {
        return [WhatsAppChannel::class, 'database'];
    }

    public function toWhatsApp($notifiable)
    {
        try {
            $user = $this->suspiciousLogin->user;
            $distance = number_format($this->suspiciousLogin->distance_km, 2);
            $mapUrl = "https://www.google.com/maps?q={$this->suspiciousLogin->login_latitude},{$this->suspiciousLogin->login_longitude}";
            
            $message = "🚨 *Suspicious Ticket Creation Alert* 🚨\n\n" .
                     "A ticket was created from a suspicious location.\n\n" .
                     "*User:* {$user->name}\n" .
                     "*Email:* {$user->email}\n" .
                     "*Distance from allowed location:* {$distance} km\n" .
                     "*Location:* [View on Map]($mapUrl)\n\n" .
                     "Please review this activity immediately.";

            // Log the notification
            Log::info('Sending suspicious location notification', [
                'user_id' => $user->id,
                'suspicious_login_id' => $this->suspiciousLogin->id
            ]);

            return $message;

        } catch (\Exception $e) {
            Log::error('Failed to prepare WhatsApp notification', [
                'error' => $e->getMessage(),
                'suspicious_login_id' => $this->suspiciousLogin->id ?? null
            ]);
            throw $e; // This will trigger the retry mechanism
        }
    }

    public function toArray($notifiable)
    {
        return [
            'suspicious_login_id' => $this->suspiciousLogin->id,
            'user_id' => $this->suspiciousLogin->user_id,
            'distance_km' => $this->suspiciousLogin->distance_km,
            'login_latitude' => $this->suspiciousLogin->login_latitude,
            'login_longitude' => $this->suspiciousLogin->login_longitude,
        ];
    }

    public function failed(\Exception $e)
    {
        Log::error('Failed to send suspicious location notification after all retries', [
            'error' => $e->getMessage(),
            'suspicious_login_id' => $this->suspiciousLogin->id ?? null
        ]);
    }
}