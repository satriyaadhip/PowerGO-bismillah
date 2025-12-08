<?php

namespace App\Console\Tasks;

use Illuminate\Console\Scheduling\Task;
use Illuminate\Support\Facades\Artisan;

class FirebaseSyncTask extends Task
{
    /**
     * Handle the task's execution.
     */
    public function handle(): void
    {
        Artisan::call('firebase:sync');
    }

    /**
     * Define the task's schedule.
     */
    public function schedule(): void
    {
        $this->everyMinute();
    }
}
