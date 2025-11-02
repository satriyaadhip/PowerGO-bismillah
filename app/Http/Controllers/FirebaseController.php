<?php

namespace App\Http\Controllers;

use App\Models\Device;
use App\Models\Record;
use App\Services\FirebaseService;
use Illuminate\Support\Carbon;

class FirebaseController extends Controller
{
    protected $firebase;

    public function __construct(FirebaseService $firebase)
    {
        $this->firebase = $firebase;
    }

    public function syncToMySQL()
    {
        $data = $this->firebase->getData('powergo/realtime');

        if (!$data) {
            return response()->json(['message' => 'Tidak ada data dari Firebase.'], 404);
        }

        $device = Device::first();

        if (!$device) {
            return response()->json(['message' => 'Tidak ada device ditemukan.'], 404);
        }

        $record = Record::create([
            'device_id' => $device->id,
            'voltage' => $data['voltage'] ?? 0,
            'amperage' => $data['amperage'] ?? 0,
            'watt' => $data['watt'] ?? 0,
            'timestamp' => Carbon::now(),
        ]);

        return response()->json([
            'message' => 'Data berhasil disimpan ke MySQL',
            'data' => $record
        ]);
    }

    // endpoint buat API realtime (dipanggil dari JS di view)
    public function getRealtime()
    {
        $data = $this->firebase->getData('powergo/realtime');
        return response()->json($data ?? []);
    }
}
