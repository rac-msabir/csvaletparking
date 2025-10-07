<?php

namespace App\Channels;

use Twilio\Rest\Client;
use App\Models\WhatsAppMessage;
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
            
            // Log the message to the database
            $this->logWhatsAppMessage([
                'phone_number' => $phoneNumber,
                'message_sid' => $twilioMessage->sid,
                'account_sid' => $config['sid'],
                'status' => $twilioMessage->status,
                'content' => $message,
                'notifiable' => $notifiable,
                'notification' => $notification,
                'twilio_response' => json_encode($twilioMessage->toArray())
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
            // Log the failed attempt
            $this->logWhatsAppMessage([
                'phone_number' => $phoneNumber,
                'status' => 'failed',
                'content' => $message,
                'notifiable' => $notifiable,
                'notification' => $notification,
                'error_message' => $e->getMessage(),
                'twilio_response' => json_encode([
                    'error' => $e->getMessage(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine()
                ])
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
    
    /**
     * Log WhatsApp message to the database
     *
     * @param array $data
     * @return WhatsAppMessage
     */
    protected function logWhatsAppMessage(array $data): WhatsAppMessage
    {
        $messageData = [
            'customer_phone' => $data['phone_number'],
            'content' => $data['content'],
            'message_type' => 'outbound',
            'status' => $data['status'] ?? 'pending',
            'message_sid' => $data['message_sid'] ?? null,
            'account_sid' => $data['account_sid'] ?? null,
            'twilio_response' => $data['twilio_response'] ?? null,
            'error_message' => $data['error_message'] ?? null,
            'attempts' => 1,
            'sent_at' => now(),
        ];
        
        // Extract customer name and email from notifiable if available
        $notifiable = $data['notifiable'] ?? null;
        if ($notifiable) {
            if (is_object($notifiable)) {
                $messageData['customer_name'] = $notifiable->name ?? $notifiable->customer_name ?? null;
                $messageData['customer_email'] = $notifiable->email ?? $notifiable->customer_email ?? null;
                
                // If notifiable is a Ticket model or has ticket_id
                if (method_exists($notifiable, 'getKey')) {
                    $messageData['ticket_id'] = $notifiable->ticket_id ?? $notifiable->id ?? null;
                    $messageData['tenant_id'] = $notifiable->tenant_id ?? null;
                }
            } elseif (is_array($notifiable)) {
                $messageData['customer_name'] = $notifiable['name'] ?? $notifiable['customer_name'] ?? null;
                $messageData['customer_email'] = $notifiable['email'] ?? $notifiable['customer_email'] ?? null;
                $messageData['ticket_id'] = $notifiable['ticket_id'] ?? $notifiable['id'] ?? null;
                $messageData['tenant_id'] = $notifiable['tenant_id'] ?? null;
            }
        }
        
        // If we have a notification, try to get template name if it's a template message
        $notification = $data['notification'] ?? null;
        if ($notification && method_exists($notification, 'getTemplateName')) {
            $messageData['template_name'] = $notification->getTemplateName();
        }
        
        // Ensure tenant_id is set, fallback to current tenant or default
        if (empty($messageData['tenant_id'])) {
            $messageData['tenant_id'] = tenant('id') ?? 1; // Fallback to 1 if no tenant context
        }
        
        return WhatsAppMessage::create($messageData);
    }
}
