<?php
namespace App\Services;

use App\Models\Record;
use Kreait\Laravel\Firebase\Facades\Firebase;

class FirebaseToSQLService
{
    protected $database;

    public function __construct()
    {
        $this->database = Firebase::database();
    }


    public function syncRecords()
    {
        // Ganti 'energy_records' sesuai node Firebase kamu
        $firebaseData = $this->database->getReference('energy_records')->getValue();

        if (!$firebaseData) return;

        foreach ($firebaseData as $item) {
            // Cek supaya tidak duplikat berdasarkan timestamp
            Record::updateOrCreate(
                ['waktu' => $item['timestamp']], 
                [
                    'voltage' => $item['V'] ?? $item['voltage'],
                    'amperage' => $item['A'] ?? $item['amperage'],
                    'watt' => $item['P'] ?? $item['watt'],
                ]
            );
        }
    }
}
