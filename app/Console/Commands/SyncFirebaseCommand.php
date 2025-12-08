<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\FirebaseSyncController;

class SyncFirebaseCommand extends Command
{
    protected $signature = 'firebase:sync';
    protected $description = 'Fetch data from Firebase and store to MySQL';

    public function handle()
    {
        try {
            // Panggil controller sync
            $controller = app(FirebaseSyncController::class);
            $response = $controller->sync();

            $this->info("Sync executed: " . $response->getData()->message);
        } catch (\Throwable $e) {
            $this->error("Sync FAILED: " . $e->getMessage());
        }
    }
}