<?php

namespace App\Channels;

use Twilio\Rest\Client;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;

class WhatsAppChannel
{
    /**
     * Send the given notification.
     *
     * @param  mixed  $notifiable
     * @param  Notification  $notification
     * @return mixed
     */
    public function send($notifiable, $notification)
    {
        if (!method_exists($notification, 'toWhatsApp')) {
            throw new \RuntimeException('Notification is missing toWhatsApp method.');
        }
        
        $message = $notification->toWhatsApp($notifiable);
        
        // Get the phone number from the notification data if available
        $phoneNumber = $notifiable->customer_phone ?? 
                     (is_array($notifiable) ? ($notifiable['customer_phone'] ?? null) : null);
        
        // If no phone number, try to get it from the notifiable
        if (empty($phoneNumber) && is_object($notifiable) && method_exists($notifiable, 'routeNotificationForWhatsApp')) {
            $phoneNumber = $notifiable->routeNotificationForWhatsApp($notification);
        }
        
        if (empty($phoneNumber)) {
            Log::warning('No WhatsApp number provided for notification', [
                'notifiable' => is_object($notifiable) ? get_class($notifiable) : 'array',
                'notification' => get_class($notification)
            ]);
            return null;
        }
        
        // Format phone number
        $phoneNumber = $this->formatPhoneNumber($phoneNumber);
        
        // Get Twilio credentials from config
        $config = config('services.twilio');
        
        if (empty($config['sid']) || empty($config['token'])) {
            Log::error('Twilio credentials not configured');
            return null;
        }
        
        try {
            $twilio = new Client($config['sid'], $config['token']);
            
            // Send WhatsApp message using Twilio's API
            $twilioMessage = $twilio->messages->create(
                "whatsapp:{$phoneNumber}", // to
                [
                    'from' => $config['whatsapp_from'],
                    'body' => $message
                ]
            );
            
            Log::info('WhatsApp message sent successfully via Twilio', [
                'message_sid' => $twilioMessage->sid,
                'phone_number' => $phoneNumber,
                'status' => $twilioMessage->status
            ]);
            
            return $twilioMessage;
            
        } catch (\Exception $e) {
            Log::error('Twilio WhatsApp notification error: ' . $e->getMessage(), [
                'phone_number' => $phoneNumber,
                'error' => [
                    'message' => $e->getMessage(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'trace' => $e->getTraceAsString()
                ]
            ]);
            throw $e; // Re-throw to let the notification system handle it
        }
    }
    
    /**
     * Format phone number for Twilio WhatsApp API
     * 
     * @param string $phoneNumber
     * @return string
     */
    protected function formatPhoneNumber(string $phoneNumber): string
    {
        // Remove all non-numeric characters
        $phoneNumber = preg_replace('/[^0-9]/', '', $phoneNumber);
        
        // If the number starts with '0', replace with country code (assuming +92 for Pakistan)
        if (strpos($phoneNumber, '0') === 0) {
            $phoneNumber = '92' . substr($phoneNumber, 1);
        }
        
        return $phoneNumber;
    }
}
