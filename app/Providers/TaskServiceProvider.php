<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Console\Scheduling\Schedule;

class TaskServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap scheduled tasks.
     */
    public function boot(Schedule $schedule): void
    {
        // Load all tasks in app/Console/Tasks automatically
        foreach (glob(app_path('Console/Tasks/*.php')) as $taskFile) {
            $class = 'App\\Console\\Tasks\\' . basename($taskFile, '.php');
            if (class_exists($class)) {
                $schedule->call(new $class)->name($class);
            }
        }
    }
}
