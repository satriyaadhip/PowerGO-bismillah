<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home');
});

Route::get('/dashboard', function() {
    return view('dashboard');
});
Route::get('/pembayaran', function() {
    return view('pembayaran');
});
Route::get('/riwayat', function() {
    return view('riwayat');
});

Route::get('/about', function() {
    return view('about');
});