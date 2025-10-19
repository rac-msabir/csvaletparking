<?php

namespace App\Channels;

use App\Models\WhatsAppMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

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
        
        // Get UltraMsg credentials from config
        $config = config('services.ultramsg');
        
        if (empty($config['instance_id']) || empty($config['token'])) {
            Log::error('UltraMsg credentials not configured');
            return null;
        }
        
        try {
            $response = $this->sendViaUltraMsg($phoneNumber, $message, $config);
            
            $isSent = isset($response['sent']) && ($response['sent'] === 'true' || $response['sent'] === true);
            
            Log::info('WhatsApp message sent via UltraMsg', [
                'response' => $response,
                'to' => $phoneNumber,
                'sent' => $isSent
            ]);
            
            // Log the message to the database
            $this->logWhatsAppMessage([
                'phone_number' => $phoneNumber,
                'message_sid' => $response['id'] ?? null,
                'status' => $isSent ? 'sent' : 'failed',
                'content' => $message,
                'notifiable' => $notifiable,
                'notification' => $notification,
                'ultramsg_response' => json_encode($response)
            ]);
            
            return $response;
            
        } catch (\Exception $e) {
            Log::error('UltraMsg WhatsApp notification error: ' . $e->getMessage(), [
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
                'ultramsg_response' => json_encode([
                    'error' => $e->getMessage(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine()
                ])
            ]);
            
            // Don't re-throw to prevent queue retries, just return the error
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }
    
    /**
     * Send message via UltraMsg API
     *
     * @param string $phoneNumber
     * @param string $message
     * @param array $config
     * @return array
     */
    protected function sendViaUltraMsg(string $phoneNumber, string $message, array $config): array
    {
        $url = "https://api.ultramsg.com/{$config['instance_id']}/messages/chat";
        
        $payload = [
            'token' => $config['token'],
            'to' => $phoneNumber,
            'body' => $message
        ];
        
        $curl = curl_init();
        
        curl_setopt_array($curl, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => json_encode($payload),
            CURLOPT_HTTPHEADER => [
                "content-type: application/json"
            ],
        ]);
        
        $response = curl_exec($curl);
        $err = curl_error($curl);
        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);
        
        if ($err) {
            throw new \Exception("cURL Error: {$err}");
        }
        
        $decodedResponse = json_decode($response, true);
        
        if (!$decodedResponse) {
            throw new \Exception("Invalid JSON response from UltraMsg API: {$response}");
        }
        
        if ($httpCode >= 400) {
            throw new \Exception("UltraMsg API error: " . ($decodedResponse['message'] ?? $response));
        }
        
        return $decodedResponse;
    }
    
    /**
     * Format phone number for UltraMsg API
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
        
        // Ensure it starts with '+'
        if (strpos($phoneNumber, '+') !== 0) {
            $phoneNumber = '+' . $phoneNumber;
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
            'account_sid' => null, // Not applicable for UltraMsg
            'twilio_response' => $data['ultramsg_response'] ?? null,
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
            $messageData['tenant_id'] = Auth::user()->tenant_id ?? null;
        }
        
        return WhatsAppMessage::create($messageData);
    }
}