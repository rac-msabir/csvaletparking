<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AdminLoginController;
use App\Http\Controllers\SuperAdmin\DashboardController as SuperAdminDashboardController;
use App\Http\Controllers\Tenant\DashboardController as TenantDashboardController;
use App\Http\Controllers\Employee\DashboardController as EmployeeDashboardController;
use Inertia\Inertia;

// Admin Authentication Routes
Route::prefix('admin')->group(function () {
    Route::get('/login', [AdminLoginController::class, 'create'])
        ->middleware('guest')
        ->name('admin.login');
        
    Route::post('/login', [AdminLoginController::class, 'store']);
    Route::post('/logout', [AdminLoginController::class, 'destroy'])
        ->middleware('auth')
        ->name('admin.logout');
});

// Redirect root to admin login
Route::get('/', function () {
    return redirect()->route('admin.login');
});

// Employee Authentication Routes
Route::prefix('employee')->group(function () {
    Route::get('/login', [App\Http\Controllers\Auth\EmployeeLoginController::class, 'create'])
        ->middleware('guest')
        ->name('employee.login');
        
    Route::post('/login', [App\Http\Controllers\Auth\EmployeeLoginController::class, 'store']);
    
    Route::post('/logout', [App\Http\Controllers\Auth\EmployeeLoginController::class, 'destroy'])
        ->middleware('auth')
        ->name('employee.logout');
});

// Protected Routes
Route::middleware(['auth'])->group(function () {
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
            // Dashboard
            Route::get('/dashboard', [TenantDashboardController::class, 'index'])
                ->name('dashboard');
                
            // Tickets Resource
            Route::resource('tickets', \App\Http\Controllers\Tenant\TicketController::class)
                ->names('tickets');
                
            // Additional ticket routes
            Route::prefix('tickets')->group(function () {
                Route::post('{ticket}/status', [\App\Http\Controllers\Tenant\TicketController::class, 'updateStatus'])
                    ->name('tickets.status.update');
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
            Route::get('/tickets', [\App\Http\Controllers\Employee\TicketController::class, 'index'])
                ->name('tickets.index');
                
            Route::get('/tickets/{ticket}', [\App\Http\Controllers\Employee\TicketController::class, 'show'])
                ->name('tickets.show');
                
            Route::get('/tickets/{ticket}/edit', [\App\Http\Controllers\Employee\TicketController::class, 'edit'])
                ->name('tickets.edit');
                
            Route::put('/tickets/{ticket}', [\App\Http\Controllers\Employee\TicketController::class, 'update'])
                ->name('tickets.update');
            
            // Employee tasks API
            Route::get('/tasks', [EmployeeDashboardController::class, 'tasks'])
                ->name('tasks');
        });
});
