<?php

namespace App\Http\Controllers\Test;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Notifications\WhatsAppTicketCreated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;

class TestWhatsAppController extends Controller
{
    public function testNotification()
    {
        try {
            // Create a test ticket in the database with all required fields
            $ticket = Ticket::create([
                'uuid' => \Illuminate\Support\Str::uuid(),
                'tenant_id' => 1,  // Assuming tenant ID 1 exists
                'ticket_number' => 'TKT-' . time(),
                'status' => 'pending',
                
                // Customer information
                'customer_name' => 'Test Customer',
                'customer_phone' => '923496127642',
                'customer_email' => 'test@example.com',
                
                // Vehicle information (required fields)
                'vehicle_make' => 'Toyota',
                'vehicle_model' => 'Corolla',
                'vehicle_color' => 'White',
                'license_plate' => 'ABC-123',
                
                // Employee tracking
                'created_by' => 1, // Assuming user ID 1 exists
                
                // Payment information (with defaults)
                'amount' => 0,
                'payment_status' => 'unpaid',
                
                // Verification code
                'verification_code' => strtoupper(\Illuminate\Support\Str::random(6)),
                'check_in_at' => now(),
            ]);
            
            // Create a notification instance
            $notification = new WhatsAppTicketCreated($ticket);
            
            // Send the notification directly without using the notifiable trait
            Notification::route('whatsapp', $ticket->customer_phone)
                ->notify($notification);
            
            return response()->json([
                'success' => true,
                'message' => 'Test notification sent successfully!',
                'ticket' => [
                    'id' => $ticket->id,
                    'number' => $ticket->ticket_number,
                    'vehicle' => $ticket->vehicle_number,
                    'location' => $ticket->location,
                    'phone' => $ticket->customer_phone
                ]
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to send notification: ' . $e->getMessage(),
                'error' => $e->getTraceAsString(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ], 500);
        }
    }
}
