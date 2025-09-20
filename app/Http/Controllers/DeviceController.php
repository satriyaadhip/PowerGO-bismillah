<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Device;

class DeviceController extends Controller
{
    public function index()
    {
        $devices = Device::orderBy('name')->paginate(12);
        
        return view('devices.index', compact('devices'));
    }

    public function show(Device $device)
    {
        $device->load(['sensorReadings' => function ($query) {
            $query->orderBy('created_at', 'desc')->take(20);
        }]);

        $recentReadings = $device->sensorReadings()
            ->select('sensor_type', 'value', 'unit', 'created_at')
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get()
            ->groupBy('sensor_type');

        return view('devices.show', compact('device', 'recentReadings'));
    }

    public function toggle(Device $device)
    {
        $device->update([
            'status' => !$device->status,
            'last_ping' => $device->status ? now() : null
        ]);

        return redirect()->back()->with('success', 
            "Device {$device->name} has been " . 
            ($device->status ? 'activated' : 'deactivated')
        );
    }
}

