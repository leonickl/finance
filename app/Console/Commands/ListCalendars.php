<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\CalDav;
use Illuminate\Console\Command;

final class ListCalendars extends Command
{
    protected $signature = 'cash:calendars';

    protected $description = 'List the remote calendars to select a task list from containing your cash transactions.';

    public function handle(): void
    {
        $calendars = CalDav::make()
            ->calendars()
            ->map(fn ($calendar) => [
                'ID' => $calendar->getCalendarId(),
                'Name' => $calendar->getDisplayname(),
            ]);

        $this->table(['ID', 'Name'], $calendars);
    }
}
