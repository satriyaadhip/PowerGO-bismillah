<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TarifListrik;

class TarifListrikSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            ['daya_va' => 450,  'tarif_per_kwh' => 415],
            ['daya_va' => 900,  'tarif_per_kwh' => 1352],
            ['daya_va' => 1300, 'tarif_per_kwh' => 1444.70],
            ['daya_va' => 2200, 'tarif_per_kwh' => 1444.70],
        ];

        foreach ($data as $row) {
            TarifListrik::updateOrCreate(
                ['daya_va' => $row['daya_va']],
                ['tarif_per_kwh' => $row['tarif_per_kwh']]
            );
        }
    }
}
