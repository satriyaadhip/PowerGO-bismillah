<?php

use Illuminate\Support\Facades\Schedule;

Schedule::command('firebase:sync')
    ->everyMinute()
    ->appendOutputTo(storage_path('logs/schedule-debug.log'));