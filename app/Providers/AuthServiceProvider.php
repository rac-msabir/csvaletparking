<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        \App\Models\Ticket::class => \App\Policies\TicketPolicy::class,
        \App\Models\SuspiciousLogin::class => \App\Policies\SuspiciousLoginPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        // Define gates for different user roles
        Gate::define('super-admin', function ($user) {
            return $user->is_super_admin;
        });

        Gate::define('tenant-admin', function ($user) {
            return $user->is_tenant_admin;
        });

        Gate::define('employee', function ($user) {
            return $user->is_employee;
        });
    }
}
