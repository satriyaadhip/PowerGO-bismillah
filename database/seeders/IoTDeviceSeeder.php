<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Device;
use App\Models\SensorReading;

class IoTDeviceSeeder extends Seeder
{
    public function run()
    {
        $devices = [
            [
                'name' => 'Living Room Sensor',
                'type' => 'Temperature & Humidity',
                'location' => 'Living Room',
                'status' => true,
                'temperature' => 22.5,
                'humidity' => 45.2,
                'battery_level' => 85,
                'last_ping' => now()->subMinutes(2),
            ],
            [
                'name' => 'Kitchen Smart Plug',
                'type' => 'Smart Plug',
                'location' => 'Kitchen',
                'status' => true,
                'temperature' => null,
                'humidity' => null,
                'battery_level' => null,
                'last_ping' => now()->subMinutes(1),
            ],
            [
                'name' => 'Bedroom Climate Monitor',
                'type' => 'Climate Sensor',
                'location' => 'Master Bedroom',
                'status' => false,
                'temperature' => 20.1,
                'humidity' => 52.8,
                'battery_level' => 23,
                'last_ping' => now()->subHours(3),
            ],
            [
                'name' => 'Garden Moisture Sensor',
                'type' => 'Soil Moisture',
                'location' => 'Garden',
                'status' => true,
                'temperature' => 18.7,
                'humidity' => 78.3,
                'battery_level' => 67,
                'last_ping' => now()->subMinutes(5),
            ],
            [
                'name' => 'Garage Door Sensor',
                'type' => 'Door Sensor',
                'location' => 'Garage',
                'status' => true,
                'temperature' => 15.2,
                'humidity' => 35.1,
                'battery_level' => 91,
                'last_ping' => now()->subSeconds(30),
            ],
            [
                'name' => 'Office Air Quality Monitor',
                'type' => 'Air Quality',
                'location' => 'Home Office',
                'status' => true,
                'temperature' => 23.1,
                'humidity' => 41.5,
                'battery_level' => 58,
                'last_ping' => now()->subMinutes(1),
            ]
        ];

        foreach ($devices as $deviceData) {
            $device = Device::create($deviceData);
            
            // Create some sample sensor readings
            $sensorTypes = ['temperature', 'humidity', 'pressure', 'light'];
            
            for ($i = 0; $i < 10; $i++) {
                SensorReading::create([
                    'device_id' => $device->id,
                    'sensor_type' => $sensorTypes[array_rand($sensorTypes)],
                    'value' => rand(10, 100) + (rand(0, 99) / 100),
                    'unit' => 'unit',
                    'created_at' => now()->subHours(rand(1, 24))
                ]);
            }
        }
    }
}