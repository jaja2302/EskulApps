<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\DashboardController;
use Spatie\Permission\Middleware\RoleMiddleware;
use Spatie\Permission\Middleware\PermissionMiddleware;
use App\Livewire\Manageuser\Managementuser;
use App\Livewire\Dashboard;
Route::get('/', function () {
    return view('auth.login');
});

// Auth Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'show'])->name('login');
    Route::post('/login', [LoginController::class, 'authenticate']);
    Route::get('/register', [RegisterController::class, 'show'])->name('register');
    Route::post('/register', [RegisterController::class, 'store']);
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
    Route::get('/dashboard', Dashboard::class)->name('dashboard');
    Route::group(['middleware' => ['role:admin']], function () {
        
    });
    // Admin routes using the new Laravel 11 syntax
    Route::middleware(RoleMiddleware::using('admin'))->group(function () {
        Route::get('/manage-users', Managementuser::class)->name('manageusers');
    });
});

