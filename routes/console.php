<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::command('erp:sync-customers')
    ->cron(config('erp.sync_schedule'))
    ->withoutOverlapping()
    ->onOneServer()
    ->appendOutputTo(storage_path('logs/erp-customer-sync.log'));
