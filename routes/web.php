<?php
  use App\Http\Controllers\GraphController;
  use App\Http\Controllers\WattController;
  use Illuminate\Support\Facades\Route;

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

Route::get('/dashboard/total_daya', [GraphController::class, 'totalDaya']);
Route::get('/dashboard/sisa_kwh', [GraphController::class, 'sisaKwh']);
Route::get('/api/realtime', [WattController::class, 'getRealtimePower']);
Route::post('/api/realtime', [WattController::class, 'setRealtimePower']);
