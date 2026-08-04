<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use App\Jobs\ProcessInboxJob;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Schedule the ProcessInboxJob to run every minute without overlapping
Schedule::job(new ProcessInboxJob)->everyMinute()->withoutOverlapping();
