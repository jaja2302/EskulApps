<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('components.login.index');
});

Route::get('/dashboard', function () {
    return view('components.dashboard.index');
});

