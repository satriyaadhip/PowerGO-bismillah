<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DayaController;
use App\Http\Controllers\GraphController;
use App\Http\Controllers\FirebaseController;
use App\Http\Controllers\FirebaseSyncController;use App\Models\User;
use App\Models\Customer;

Route::get('/debug/customer-test/{id}', function ($id) {

    $user = User::find($id);
    if (!$user) return 'User not found';

    try {
        $pel = random_int(1000000000, 9999999999);

        $customer = Customer::create([
            'user_id'      => $user->id,
            'pelanggan_id' => (string)$pel,
            'daya_va'      => 1300,
            'max_watt'     => 1300,
            'billing_type' => 'prabayar'
        ]);

        return "Customer created successfully: ID = {$customer->id}";

    } catch (\Throwable $e) {
        return "ERROR: ".$e->getMessage();
    }
});


// ==============================
// PUBLIC
// ==============================
Route::get('/', fn() => view('home'))->name('home');


// ==============================
// AUTH PROTECTED (web pages)
// ==============================
Route::middleware(['auth', 'verified'])->group(function () {

    // Dashboard
    Route::get('/dashboard', [GraphController::class, 'summary'])->name('dashboard');

    // Graph pages
    Route::prefix('dashboard')->group(function () {
        Route::get('/total_daya/{date?}', [GraphController::class, 'totalDaya'])->name('dashboard.total_daya');
        Route::get('/sisa_kwh', [GraphController::class, 'sisaKwh'])->name('dashboard.sisa_kwh');
    });

    // Pembayaran
    Route::get('/pembayaran', fn() => view('pembayaran.pembayaran'))->name('pembayaran');
});


// ==============================
// API (tanpa auth) — untuk ESP32 / Firebase
// ==============================
Route::prefix('api')->group(function () {
    Route::get('/realtime', [FirebaseController::class, 'getRealtimeData'])->name('api.realtime');
    Route::post('/realtime', [DayaController::class, 'setRealtimePower'])->name('api.realtime.post');
    Route::get('/realtime/device', [DayaController::class, 'getRealtimePower'])->name('api.realtime.get');
});


// ==============================
// SYNC
// ==============================
Route::get('/firebase/sync', [FirebaseSyncController::class, 'sync'])->name('firebase.sync');


// ==============================
// AUTH ROUTES
// ==============================
require __DIR__.'/auth.php';
