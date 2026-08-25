<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AttendanceController;


/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
*/

// Login page
Route::get('/login', [
    AuthController::class,
    'showLogin'
])->name('login');


// Login request
Route::post('/login', [
    AuthController::class,
    'login'
]);


// Register page
Route::get('/register', [
    AuthController::class,
    'showRegister'
])->name('register');


// Register request
Route::post('/register', [
    AuthController::class,
    'register'
]);


// Logout
Route::post('/logout', [
    AuthController::class,
    'logout'
])
    ->middleware('auth')
    ->name('logout');


/*
|--------------------------------------------------------------------------
| Dashboard
|--------------------------------------------------------------------------
*/

Route::get('/dashboard', function () {

    return view('dashboard.dashboard');

})
    ->middleware('auth')
    ->name('dashboard');


/*
|--------------------------------------------------------------------------
| Attendance Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Attendance Page
    |--------------------------------------------------------------------------
    |
    | GET /attendance
    |
    | Returns:
    | resources/views/attendance.blade.php
    |
    */

    Route::get(
        '/attendance',
        [AttendanceController::class, 'index']
    )->name('attendance.index');


    /*
    |--------------------------------------------------------------------------
    | Fetch Attendance Data
    |--------------------------------------------------------------------------
    |
    | GET /api/attendance/data
    |
    | Used by fetch() inside attendance.blade.php
    |
    */

    Route::get(
        '/api/attendance/data',
        [AttendanceController::class, 'data']
    )->name('attendance.data');


    /*
    |--------------------------------------------------------------------------
    | Save New Attendance
    |--------------------------------------------------------------------------
    |
    | POST /api/attendance/course/{course}
    |
    */

    Route::post(
        '/api/attendance/course/{course:course_id}',
        [AttendanceController::class, 'storeApi']
    )->name('attendance.store.api');


    /*
    |--------------------------------------------------------------------------
    | Update Existing Attendance
    |--------------------------------------------------------------------------
    |
    | PUT /api/attendance/lecture/{lecture}
    |
    */

    Route::put(
        '/api/attendance/lecture/{lecture:lecture_id}',
        [AttendanceController::class, 'updateApi']
    )->name('attendance.update.api');

});