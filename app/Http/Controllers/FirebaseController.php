<?php

namespace App\Http\Controllers;

use App\Services\FirebaseService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Record;

class FirebaseController extends Controller
{
    protected $firebase;

    public function __construct(FirebaseService $firebase)
    {
        $this->firebase = $firebase;
    }

    /**
     * Mendapatkan data realtime dari Firebase, jika gagal fallback ke database.
     * Endpoint: /api/realtime (GET)
     */
    public function getRealtimeData()
    {
        // Coba ambil data realtime dari Firebase
        $raw = $this->firebase->getData('powergo/realtime');

        // Siapkan fallback null agar konsisten
        $fallback = [
            'device_id' => 0,
            'voltage' => 0.0,
            'amperage' => 0.0,
            'watt' => 0.0,
            'timestamp' => now()->toDateTimeString(),
            'source' => 'none'
        ];

        // Jika dapat data array dari Firebase dan minimal ada 1 key utama
        if (!empty($raw) && is_array($raw)) {
            $data = [
                'device_id' => $raw['device_id'] ?? ($raw['deviceId'] ?? 0),
                'voltage' => isset($raw['voltage']) ? (float)$raw['voltage'] : (float)($raw['voltage_v'] ?? 0),
                'amperage' => isset($raw['amperage']) ? (float)$raw['amperage'] : (float)($raw['current'] ?? 0),
                'watt' => isset($raw['watt']) ? (float)$raw['watt'] : (float)($raw['power'] ?? 0),
                'timestamp' => $raw['timestamp'] ?? now()->toDateTimeString(),
                'source' => 'firebase'
            ];
            return response()->json($data);
        }

        // Jika object stdClass, cast ke array dan ulangi proses
        if (!empty($raw) && is_object($raw)) {
            $raw = (array) $raw;
            $data = [
                'device_id' => $raw['device_id'] ?? ($raw['deviceId'] ?? 0),
                'voltage' => isset($raw['voltage']) ? (float)$raw['voltage'] : (float)($raw['voltage_v'] ?? 0),
                'amperage' => isset($raw['amperage']) ? (float)$raw['amperage'] : (float)($raw['current'] ?? 0),
                'watt' => isset($raw['watt']) ? (float)$raw['watt'] : (float)($raw['power'] ?? 0),
                'timestamp' => $raw['timestamp'] ?? now()->toDateTimeString(),
                'source' => 'firebase'
            ];
            return response()->json($data);
        }

        // Jika firebase null/empty, fallback ke MySQL: ambil record terbaru
        $latest = Record::orderBy('timestamp', 'desc')->first();
        if ($latest) {
            return response()->json([
                'device_id' => $latest->device_id ?? 0,
                'voltage' => (float) $latest->voltage,
                'amperage' => (float) $latest->amperage,
                'watt' => (float) $latest->watt,
                'timestamp' => $latest->timestamp instanceof \Carbon\Carbon ? $latest->timestamp->toDateTimeString() : (string) $latest->timestamp,
                'source' => 'mysql'
            ]);
        }

        // Fallback terakhir, semua nilai 0
        return response()->json($fallback);
    }
}