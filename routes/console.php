<?php

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('transactions:delete-expired', function () {
    $deleted = DB::table('transactions')
        ->where('expired_date', '<', Carbon::now())
        ->where('status', 'pending')
        ->update(['status' => 'expired']);

    $this->info("$deleted expired transactions.");
})->purpose('Change status expired transactions')->daily();