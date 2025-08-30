<?php

namespace App\Filament\Widgets;

use App\Statistics\Budget\YearlyBudget;
use App\Types\Date\Year;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class YearlyInfo extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        $budget = new YearlyBudget(Year::now()->plus(session('lag')));

        return [
            Stat::make('Income', $budget->incomeSum())
                ->chart($budget->incomePerMonth())
                ->color('success'),
            Stat::make('Total', $budget->balance())
                ->color('gray'),
            Stat::make('Expenses', $budget->expensesSum())
                ->chart($budget->expensesPerMonth())
                ->color('danger'),
        ];
    }
}
