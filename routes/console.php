<?php

use Illuminate\Support\Facades\Schedule;

Schedule::command('firebase:sync')
    ->everyMinute()
    ->appendOutputTo(storage_path('logs/schedule-debug.log'));

// Update kwh_balance for all prabayar customers every minute
Schedule::command('kwh-balance:update')
    ->everyMinute()
    ->appendOutputTo(storage_path('logs/kwh-balance-update.log'));