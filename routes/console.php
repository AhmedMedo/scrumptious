<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

Schedule::command('notifications:send-target-reminders')->daily();
Schedule::command('notifications:process-scheduled-broadcasts')->everyMinute();
Schedule::command('notifications:clean-old-notifications')->weekly();
Schedule::command('telescope:prune --hours=48')->daily();
