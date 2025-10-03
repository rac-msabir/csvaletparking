<?php

namespace Database\Seeders;

use App\Models\Tenant;
use Illuminate\Database\Seeder;

class TenantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create a test tenant with only required fields
        Tenant::firstOrCreate(
            ['email' => 'test-tenant@example.com'],
            [
                'name' => 'Test Tenant',
                'slug' => 'test-tenant',
                'email' => 'test-tenant@example.com',
                'phone' => '1234567890',
                'is_active' => true,
            ]
        );
    }
}
