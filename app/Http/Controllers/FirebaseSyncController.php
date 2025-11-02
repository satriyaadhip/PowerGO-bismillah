<?php

namespace App\Http\Controllers;

use App\Services\FirebaseService;
use App\Models\Record;
use Illuminate\Http\JsonResponse;

class FirebaseSyncController extends Controller
{
    protected $firebase;

    public function __construct(FirebaseService $firebase)
    {
        $this->firebase = $firebase;
    }

    public function sync(): JsonResponse
    {
        // Sesuaikan dengan path data kamu di Firebase Realtime DB
        $path = 'sensorData'; // contoh: root node berisi voltage, amperage, watt
        $data = $this->firebase->getData($path);

        if (!$data) {
            return response()->json(['message' => 'Tidak ada data dari Firebase.'], 404);
        }

        // Pastikan ada field yang dibutuhkan
        if (isset($data['voltage'], $data['amperage'], $data['watt'])) {
            Record::create([
                'voltage' => $data['voltage'],
                'amperage' => $data['amperage'],
                'watt' => $data['watt'],
                'timestamp' => now(),
            ]);
            return response()->json(['message' => 'Data berhasil disimpan ke MySQL.']);
        }

        return response()->json(['message' => 'Format data Firebase tidak sesuai.'], 400);
    }
}
