<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;


Route::get('/login', [
    AuthController::class,
    'showLogin'
])->name('login');

Route::post('/login', [
    AuthController::class,
    'login'
]);

Route::get('/register', [
    AuthController::class,
    'showRegister'
])->name('register');

Route::post('/register', [
    AuthController::class,
    'register'
]);


Route::post('/logout', [
    AuthController::class,
    'logout'
])->middleware('auth')->name('logout');


Route::get('/dashboard', function () {
    return view('dashboard.dashboard');
})->middleware('auth')->name('dashboard');


Route::get('/attendance', function () {
    return view('attendance');
})->middleware('auth')->name('attendance');