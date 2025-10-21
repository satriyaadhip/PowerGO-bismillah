<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\FirebaseToSQLService;

class SyncFirebaseRecords extends Command
{
    protected $signature = 'sync:firebase-records';
    protected $description = 'Sinkronisasi data Firebase ke MySQL';

    protected $service;

    public function __construct(FirebaseToSQLService $service)
    {
        parent::__construct();
        $this->service = $service;
    }

    public function handle()
    {
        $this->info('Mulai sinkronisasi Firebase...');
        $this->service->syncRecords();
        $this->info('Selesai sinkronisasi.');
    }
}
