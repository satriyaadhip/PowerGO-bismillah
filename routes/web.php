<?php

use App\Http\Controllers\DayaController;
use App\Http\Controllers\GraphController;
use App\Http\Controllers\FirebaseController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home');
});

// Dashboard view
Route::get('/dashboard', function() {
    return view('dashboard.dashboard');
});

// Graph pages
Route::get('/dashboard/total_daya', [GraphController::class, 'totalDaya'])->name('dashboard.total_daya');
Route::get('/dashboard/sisa_kwh', [GraphController::class, 'sisaKwh'])->name('dashboard.sisa_kwh');

// Firebase-related (single source for GET realtime)
Route::get('/api/realtime', [FirebaseController::class, 'getRealtimeData'])->name('api.realtime');

// Device endpoints (device posts current power)
Route::post('/api/realtime', [DayaController::class, 'setRealtimePower'])->name('api.realtime.post');
Route::get('/api/realtime/device', [DayaController::class, 'getRealtimePower'])->name('api.realtime.get');

// Sync endpoint (run manually or by scheduler)
Route::get('/firebase/sync', [FirebaseController::class, 'syncToMySQL'])->name('firebase.sync');
