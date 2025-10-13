<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AdminLoginController;
use App\Http\Controllers\SuperAdmin\DashboardController as SuperAdminDashboardController;
use App\Http\Controllers\Tenant\DashboardController as TenantDashboardController;
use App\Http\Controllers\Employee\DashboardController as EmployeeDashboardController;
use App\Http\Controllers\Employee\TicketController as EmployeeTicketController;
use App\Http\Controllers\Tenant\TicketController as TenantTicketController;
use App\Http\Controllers\Public\TicketController as PublicTicketController;
use Inertia\Inertia;

// Admin Authentication Routes
Route::prefix('admin')->group(function () {
    Route::get('/login', [AdminLoginController::class, 'create'])
        ->middleware('guest')
        ->name('admin.login');

    Route::post('/login', [AdminLoginController::class, 'store']); //wrong
    Route::post('/logout', [AdminLoginController::class, 'destroy'])
        ->middleware('auth')
        ->name('admin.logout');
});

// Public ticket view route
Route::get('/tickets/{token}', [PublicTicketController::class, 'show'])
    ->middleware('active')
    ->name('tickets.public.show');

// Redirect root to admin login
Route::get('/', function () {
    return redirect()->route('admin.login');
});

// Employee Authentication Routes
Route::prefix('employee')->group(function () {
    Route::get('/login', [AdminLoginController::class, 'store'])
        ->middleware('guest', 'active')
        ->name('employee.login');

    Route::post('/login', [EmployeeTicketController::class, 'store']); //wrong

    Route::post('/logout', [EmployeeTicketController::class, 'destroy'])
        ->middleware('auth')
        ->name('employee.logout');
});

// Protected Routes
Route::middleware(['auth', 'active'])->group(function () {
    // Dashboard Route
    Route::get('/dashboard', function () {
        $user = auth()->user();

        if ($user->is_super_admin) {
            return redirect()->route('super-admin.dashboard');
        } elseif ($user->is_tenant_admin) {
            return redirect()->route('tenant.dashboard');
        }
        return redirect()->route('employee.dashboard');
    })->name('dashboard');

    // Super Admin Routes
    Route::prefix('super-admin')
        ->name('super-admin.')
        ->middleware('can:super-admin')
        ->group(function () {
            Route::get('/dashboard', [SuperAdminDashboardController::class, 'index'])
                ->name('dashboard');
        });

    // Tenant Admin Routes
    Route::prefix('tenant')
        ->name('tenant.')
        ->middleware('can:tenant-admin')
        ->group(function () {

            // Tickets Resource
            // Dashboard
            Route::get('/dashboard', [TenantDashboardController::class, 'index'])
                ->name('dashboard');
            // Tickets
            Route::get('/tickets', [TenantTicketController::class, 'index'])
                ->name('tickets.index');

            Route::get('/tickets/create', [TenantTicketController::class, 'create'])
                ->middleware('check-ticket-location')
                ->name('tickets.create');

            Route::post('/tickets/store', [TenantTicketController::class, 'store'])
                ->middleware('check-ticket-location')
                ->name('tickets.store');

            Route::get('/tickets/{ticket}', [TenantTicketController::class, 'show'])
                ->name('tickets.show');

            Route::get('/tickets/{ticket}/edit', [TenantTicketController::class, 'edit'])
                ->name('tickets.edit');

            Route::put('/tickets/{ticket}', [TenantTicketController::class, 'update'])
                ->name('tickets.update');


            // Employee tasks API
            Route::get('/tasks', [EmployeeDashboardController::class, 'tasks'])
                ->name('tasks');

            // Additional ticket routes
            Route::prefix('tickets')->group(function () {
                Route::post('{ticket}/status', [TenantTicketController::class, 'updateStatus'])
                    ->name('tickets.status.update');
                Route::get('{ticket}/print', [TenantTicketController::class, 'print'])
                    ->name('tickets.print');
            });
        });

    // Employee Routes
    Route::prefix('employee')
        ->name('employee.')
        ->middleware('can:employee')
        ->group(function () {
            // Dashboard
            Route::get('/dashboard', [EmployeeDashboardController::class, 'index'])
                ->name('dashboard');

            // Tickets
            Route::get('/tickets', [EmployeeTicketController::class, 'index'])
                ->name('tickets.index');

            Route::get('/tickets/create', [EmployeeTicketController::class, 'create'])
                ->middleware('check-ticket-location')
                ->name('tickets.create');

            Route::post('/tickets/store', [EmployeeTicketController::class, 'store'])
                ->middleware('check-ticket-location')
                ->name('tickets.store');

            Route::get('/tickets/{ticket}', [EmployeeTicketController::class, 'show'])
                ->name('tickets.show');

            Route::get('/tickets/{ticket}/edit', [EmployeeTicketController::class, 'edit'])
                ->name('tickets.edit');

            Route::put('/tickets/{ticket}', [EmployeeTicketController::class, 'update'])
                ->name('tickets.update');

            Route::get('/tickets/{ticket}/print', [EmployeeTicketController::class, 'print'])
                ->name('tickets.print');

            Route::post('/tickets/{ticket}/status', [EmployeeTicketController::class, 'updateStatus'])
                ->name('tickets.status.update');

            // Employee tasks API
            Route::get('/tasks', [EmployeeDashboardController::class, 'tasks'])
                ->name('tasks');
        });
});
