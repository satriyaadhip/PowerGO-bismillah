<?php

namespace App\Http\Controllers;

use App\Services\FirebaseService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Exception;
use App\Models\Record;

class DayaController extends Controller
{
    protected $firebase;

    public function __construct(FirebaseService $firebase)
    {
        $this->firebase = $firebase;
    }

    // 🔹 Endpoint untuk baca data total daya
    public function setRealtimePower(Request $request)
    {
        $payload = $request->only(['device_id','voltage','amperage','watt','timestamp']);
    
        // Simple validation
        if (!isset($payload['watt'])) {
            return response()->json(['message' => 'watt missing'], 422);
        }
    
        // Save to MySQL (optional)
        Record::create([
            'device_id' => $payload['device_id'] ?? 1,
            'voltage'   => $payload['voltage'] ?? 0,
            'amperage'  => $payload['amperage'] ?? ($payload['watt'] / ($payload['voltage'] ?? 1)),
            'watt'      => $payload['watt'],
            'timestamp' => $payload['timestamp'] ?? now(),
        ]);
    
        // Optionally push to Firebase if service exists
        if (app()->bound(\App\Services\FirebaseService::class)) {
            try {
                app(\App\Services\FirebaseService::class)->setData('powergo/realtime', $payload);
            } catch (Exception $e) {
                Log::warning('Push to Firebase failed: '.$e->getMessage());
            }
        }
    
        return response()->json(['message' => 'OK']);
    }
    
    public function getRealtimePower()
    {
        // simple wrapper to read latest from MySQL
        $latest = \App\Models\Record::orderBy('timestamp','desc')->first();
        if (!$latest) return response()->json(['watt' => 0]);
        return response()->json([
            'device_id' => $latest->device_id,
            'voltage' => (float)$latest->voltage,
            'amperage' => (float)$latest->amperage,
            'watt' => (float)$latest->watt,
            'timestamp' => $latest->timestamp->toDateTimeString()
        ]);
    }

}