<?php

use App\Http\Controllers\DayaController;
    use App\Http\Controllers\GraphController;
    use App\Http\Controllers\RecordController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FirebaseController;

Route::get('/', function () {
    return view('home');
});

Route::get('/dashboard', function() {
    return view('dashboard.dashboard');
});
Route::get('/pembayaran', function() {
    return view('pembayaran.pembayaran');
});
Route::get('/riwayat', function() {
    return view('riwayat.riwayat');
});
Route::get('/dashboard/total_daya', function() {
    return view('dashboard.total_daya');
});
Route::get('/dashboard/sisa_kwh', function() {
    return view('dashboard.sisa_kwh');
});
Route::get('/riwayat', function() {
    return view('riwayat.riwayat');
});
Route::get('/about', function() {
    return view('about');
});

Route::get('/', [DayaController::class, 'index']);
Route::get('/firebase/sync', [FirebaseController::class, 'syncToMySQL']);
Route::get('/api/realtime', [FirebaseController::class, 'getRealtime']);
Route::get('/dashboard/total_daya', [GraphController::class, 'totalDaya'])->name('dashboard.total_daya');
Route::get('/dashboard/sisa_kwh', [GraphController::class, 'sisaKwh'])->name('dashboard.sisa_kwh');
Route::get('/api/realtime', [DayaController::class, 'getRealtimePower']);
Route::post('/api/realtime', [DayaController::class, 'setRealtimePower']);
Route::get('/firebase/sync', [FirebaseController::class, 'syncToMySQL']);


Route::get('/total-daya', [GraphController::class, 'totalDaya'])->name('total_daya');
Route::get('/sisa-kwh', [GraphController::class, 'sisaKwh'])->name('sisa_kwh');
