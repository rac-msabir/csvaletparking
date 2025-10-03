<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Super Admin
        User::firstOrCreate(
            ['email' => 'superadmin@example.com'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('password'),
                'is_super_admin' => true,
                'is_tenant_admin' => false,
                'is_employee' => false,
                'is_active' => true,
                'domain' => 'superadmin',
                'email_verified_at' => now(),
            ]
        );

        // Create Tenant Admin
        User::firstOrCreate(
            ['email' => 'tenantadmin@example.com'],
            [
                'name' => 'Tenant Admin',
                'password' => Hash::make('password'),
                'is_super_admin' => false,
                'is_tenant_admin' => true,
                'is_employee' => false,
                'is_active' => true,
                'tenant_id' => 1, // Assuming tenant with ID 1 exists
                'email_verified_at' => now(),
            ]
        );

        // Create Employee
        User::firstOrCreate(
            ['email' => 'employee@example.com'],
            [
                'name' => 'Employee User',
                'password' => Hash::make('password'),
                'is_super_admin' => false,
                'is_tenant_admin' => false,
                'is_employee' => true,
                'is_active' => true,
                'tenant_id' => 1, // Assuming tenant with ID 1 exists
                'email_verified_at' => now(),
            ]
        );

        $this->command->info('Test users created successfully!');
        $this->command->info('Super Admin: superadmin@example.com / password');
        $this->command->info('Tenant Admin: tenantadmin@example.com / password');
        $this->command->info('Employee: employee@example.com / password');
    }
}
