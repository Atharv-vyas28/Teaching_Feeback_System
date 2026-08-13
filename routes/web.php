<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/hello', function () {
    return view('hello.hello',["name" => "Atharv"]);
});
Route::get('/attendance', function () {
    return view('attendance');
});
