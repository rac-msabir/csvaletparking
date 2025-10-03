<?php

namespace Database\Seeders;

use App\Models\Ticket;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class TicketSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing data
        \App\Models\Activity::truncate();
        \App\Models\Ticket::truncate();

        // Get or create a tenant admin user
        $admin = User::where('email', 'admin@example.com')->first();
        
        if (!$admin) {
            $admin = User::factory()->create([
                'name' => 'Admin User',
                'email' => 'admin@example.com',
                'password' => bcrypt('password'),
                'is_tenant_admin' => true,
                'tenant_id' => 1, // Ensure tenant_id is set
            ]);
        } else {
            // Update existing admin to ensure it has tenant_id
            $admin->update(['tenant_id' => $admin->tenant_id ?? 1]);
        }
        
        // Ensure we have a tenant_id to work with
        $tenantId = $admin->tenant_id;

        // Create sample tickets
        $statuses = [
            Ticket::STATUS_PENDING,
            Ticket::STATUS_IN_PROGRESS,
            Ticket::STATUS_READY,
            Ticket::STATUS_DELIVERED,
            Ticket::STATUS_CANCELLED,
        ];

        $vehicleMakes = ['Toyota', 'Honda', 'Ford', 'BMW', 'Mercedes', 'Audi', 'Tesla'];
        $vehicleModels = ['Camry', 'Civic', 'F-150', '3 Series', 'C-Class', 'A4', 'Model 3'];
        $colors = ['Red', 'Blue', 'Black', 'White', 'Silver', 'Gray'];

        for ($i = 0; $i < 20; $i++) {
            $status = $statuses[array_rand($statuses)];
            $make = $vehicleMakes[array_rand($vehicleMakes)];
            $model = $vehicleModels[array_rand($vehicleModels)];
            $color = $colors[array_rand($colors)];
            
            $ticket = Ticket::create([
                'uuid' => (string) Str::uuid(),
                'ticket_number' => 'TKT-' . str_pad($i + 1, 4, '0', STR_PAD_LEFT),
                'status' => $status,
                'customer_name' => fake()->name(),
                'customer_phone' => fake()->phoneNumber(),
                'customer_email' => fake()->safeEmail(),
                'vehicle_make' => $make,
                'vehicle_model' => $model,
                'vehicle_color' => $color,
                'license_plate' => strtoupper(Str::random(2)) . ' ' . rand(1000, 9999) . ' ' . strtoupper(Str::random(2)),
                'parking_spot' => 'A-' . rand(1, 50),
                'parking_zone' => 'Zone ' . chr(rand(65, 68)), // A, B, C, or D
                'special_instructions' => rand(0, 1) ? fake()->sentence() : null,
                'damage_notes' => rand(0, 1) ? fake()->paragraph() : null,
                'check_in_at' => now(),
                'check_out_at' => $status === Ticket::STATUS_DELIVERED ? now()->addHours(rand(1, 72)) : null,
                'ready_at' => in_array($status, [Ticket::STATUS_READY, Ticket::STATUS_DELIVERED]) ? now() : null,
                'delivered_at' => $status === Ticket::STATUS_DELIVERED ? now() : null,
                'amount' => rand(500, 5000) / 100, // Random amount between 5.00 and 50.00
                'payment_status' => $status === Ticket::STATUS_DELIVERED ? 'paid' : 'pending',
                'tenant_id' => $tenantId,
                'created_by' => $admin->id,
                'assigned_to' => rand(0, 1) ? User::inRandomOrder()->first()?->id : null,
            ]);

            // Add some activity
            \App\Models\Activity::create([
                'description' => 'Ticket created',
                'subject_id' => $ticket->id,
                'subject_type' => \App\Models\Ticket::class,
                'causer_id' => $admin->id,
                'causer_type' => \App\Models\User::class,
                'properties' => [],
            ]);

            if ($status !== Ticket::STATUS_PENDING) {
                \App\Models\Activity::create([
                    'description' => 'Ticket status changed to ' . $status,
                    'subject_id' => $ticket->id,
                    'subject_type' => \App\Models\Ticket::class,
                    'causer_id' => $admin->id,
                    'causer_type' => \App\Models\User::class,
                    'properties' => ['status' => $status],
                    'event' => 'status_changed',
                ]);
            }
        }
    }
}
