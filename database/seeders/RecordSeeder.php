<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Device;
use App\Models\Record;
use Carbon\Carbon;

class RecordSeeder extends Seeder
{
    public function run(): void
    {
        // Ambil device pertama (Main Device)
        $device = Device::first();

        if (!$device) {
            $this->command->warn('⚠️ Tidak ada device ditemukan. Jalankan PowergoSeeder dulu.');
            return;
        }

        // Hapus data lama biar bersih
        Record::where('device_id', $device->id)->delete();

        $now = Carbon::now();

        // Simulasi data untuk 24 jam terakhir
        for ($i = 23; $i >= 0; $i--) {
            $time = $now->copy()->subHours($i);
            
            // Random voltage (220–240), amperage (0.1–5), watt = V*A
            $voltage = rand(220, 240) + (rand(0, 99) / 100);
            $amperage = rand(10, 500) / 100; // 0.10–5.00 A
            $watt = round($voltage * $amperage, 2);

            Record::create([
                'device_id' => $device->id,
                'voltage' => $voltage,
                'amperage' => $amperage,
                'watt' => $watt,
                'timestamp' => $time,
            ]);
        }

        $this->command->info('✅ RecordSeeder berhasil: 24 data dummy telah ditambahkan.');
    }
}
