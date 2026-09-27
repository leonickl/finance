<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\CalDav;

class ListCalendars extends Command
{
    protected $signature = 'cash:calendars';

    protected $description = 'List the remote calendars to select a task list from containing your cash transactions.';

    public function handle()
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
