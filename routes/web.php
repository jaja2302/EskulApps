<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\DashboardController;
use Spatie\Permission\Middleware\RoleMiddleware;
use Spatie\Permission\Middleware\PermissionMiddleware;
use App\Livewire\Manageuser\Managementuser;
use App\Livewire\UserProfile\Profiledetail;
use App\Livewire\Dashboard;
use App\Livewire\EksulApps\DashboardEskul;
use App\Livewire\EksulApps\DetailEskul;
use App\Livewire\AnalisisApps\DetailSiswa;
use App\Livewire\EksulApps\DetailEvent;
use App\Livewire\EksulApps\EskulAnalisis;

Route::get('/', function () {
    return view('components.Login.index');
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
    Route::get('/dashboard/analisis/detail-siswa/{hash}', DetailSiswa::class)->name('analisis-apps.detail-siswa');
    Route::get('/profile', Profiledetail::class)->name('profile.show');

    // Admin routes using the new Laravel 11 syntax
    Route::middleware(RoleMiddleware::using('admin'))->group(function () {
        Route::get('/manage-users', Managementuser::class)->name('manageusers');
       
        Route::get('/user/profile/{hash}', Profiledetail::class)->name('user.profile');
        Route::get('/dashboard/eskul/analisis/{hash}', EskulAnalisis::class)->name('eskul.analisis');
    });

    Route::middleware(PermissionMiddleware::using('view eskul'))->group(function () {
        Route::get('/dashboard/eskul', DashboardEskul::class)->name('dashboard.eskul');
        Route::get('/dashboard/eskul/detail/{hash}', DetailEskul::class)->name('eskul.detail');
    });

    Route::middleware(PermissionMiddleware::using('create event'))->group(function () {
        Route::get('/dashboard/eskul/list-event/{hash}', DetailEvent::class)->name('eskul.list-event');
    });
});


