<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RecordController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| File ini digunakan untuk mendefinisikan semua endpoint API kamu.
| Prefix default-nya sudah '/api', jadi cukup tulis '/records' dsb.
|
| Contoh: akses melalui http://localhost:8000/api/records
|
*/

// ✅ Route standar CRUD
Route::get('/records', [RecordController::class, 'index']);      // Ambil semua record
Route::get('/records/{id}', [RecordController::class, 'show']);  // Ambil satu record by ID
Route::post('/records', [RecordController::class, 'store']);     // Tambah record baru
Route::put('/records/{id}', [RecordController::class, 'update']); // Update record
Route::delete('/records/{id}', [RecordController::class, 'destroy']); // Hapus record


/*
|--------------------------------------------------------------------------
| (Opsional) Auth middleware pakai Sanctum
|--------------------------------------------------------------------------
| Kalau nanti kamu mau proteksi endpoint ini, tinggal aktifkan Sanctum.
| Misal hanya device tertentu atau user login yang boleh post/update.
|
| Contoh:
|
| Route::middleware('auth:sanctum')->group(function () {
|     Route::post('/records', [RecordController::class, 'store']);
|     Route::put('/records/{id}', [RecordController::class, 'update']);
|     Route::delete('/records/{id}', [RecordController::class, 'destroy']);
| });
|
*/


/*
|--------------------------------------------------------------------------
| (Opsional) Versi lebih singkat pakai apiResource
|--------------------------------------------------------------------------
| Kalau kamu mau pakai resource controller:
|
| Route::apiResource('records', RecordController::class);
|
| Itu otomatis buat 5 route:
| GET /records        → index
| GET /records/{id}   → show
| POST /records       → store
| PUT /records/{id}   → update
| DELETE /records/{id}→ destroy
|
*/
