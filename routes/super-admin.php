<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SuperAdmin\DashboardController;
use App\Http\Controllers\SuperAdmin\Auth\LoginController;
use App\Http\Controllers\SuperAdmin\TenantController;
use App\Http\Controllers\SuperAdmin\UserController;

/*
|--------------------------------------------------------------------------
| Super Admin Routes
|--------------------------------------------------------------------------
|
| Here is where you can register Super Admin routes for your application.
| These routes are loaded by the RouteServiceProvider within a group which
| is assigned the "web" middleware group.
|
*/

// Public routes (no authentication required)
Route::middleware('guest')
    ->prefix('super-admin')
    ->name('super-admin.')
    ->group(function () {
        // Show login form
        Route::get('/login', [LoginController::class, 'showLoginForm'])
            ->name('login');
        
        // Handle login
        Route::post('/login', [LoginController::class, 'login'])
            ->name('login.submit');
    });

// Protected routes (authentication and superadmin role required)
Route::middleware(['auth', 'verified', 'superadmin'])
    ->prefix('super-admin')
    ->name('super-admin.')
    ->group(function () {
        // Dashboard
        Route::get('/dashboard', [DashboardController::class, 'index'])
            ->name('dashboard');
        
        // Logout
        Route::post('/logout', [LoginController::class, 'logout'])
            ->name('logout');
        
        // Tenant Management
        Route::prefix('tenants')->name('tenants.')->group(function () {
            Route::get('/', [TenantController::class, 'index'])->name('index');
            Route::get('/create', [TenantController::class, 'create'])->name('create');
            Route::post('/', [TenantController::class, 'store'])->name('store');
            Route::get('/{tenant}/edit', [TenantController::class, 'edit'])->name('edit');
            Route::put('/{tenant}', [TenantController::class, 'update'])->name('update');
            Route::delete('/{tenant}', [TenantController::class, 'destroy'])->name('destroy');
            Route::post('/{tenant}/toggle-status', [TenantController::class, 'toggleStatus'])->name('toggle-status');
        });

        // User Management
        Route::prefix('users')->name('users.')->group(function () {
            Route::get('/', [UserController::class, 'index'])->name('index');
            Route::get('/create', [UserController::class, 'create'])->name('create');
            Route::post('/', [UserController::class, 'store'])->name('store');
            Route::get('/{user}/edit', [UserController::class, 'edit'])->name('edit');
            Route::put('/{user}', [UserController::class, 'update'])->name('update');
            Route::delete('/{user}', [UserController::class, 'destroy'])->name('destroy');
            Route::post('/{user}/toggle-status', [UserController::class, 'toggleStatus'])->name('toggle-status');
        });

        // // Profile
        // Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
        // Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
        // Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password.update');
    });
