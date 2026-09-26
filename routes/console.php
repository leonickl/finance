<?php

declare(strict_types=1);

use App\Jobs\CreateMissingStreaks;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function (): void {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::call(new CreateMissingStreaks)
    ->name('create-missing-streaks')
    ->everyFifteenMinutes();
