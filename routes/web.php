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
            Route::get('/dashboard', [TenantDashboardController::class, 'index'])
                ->name('dashboard');
        });

    // Employee Routes
    Route::prefix('employee')
        ->name('employee.')
        ->middleware('can:employee')
        ->group(function () {
            Route::get('/dashboard', [EmployeeDashboardController::class, 'index'])
                ->name('dashboard');
            
            // Employee tasks API
            Route::get('/tasks', [EmployeeDashboardController::class, 'tasks'])
                ->name('tasks');
        });
});
