<?php

namespace App\Filament\Widgets;

use App\Statistics\Budget\MonthlyBudget;
use App\Types\Date\Month;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class MonthlyInfo extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        $budget = new MonthlyBudget(Month::now()->plus(session('lag')));

        return [
            Stat::make('Income', $budget->incomeSum())
                ->chart($budget->incomePerDay())
                ->color('success'),
            Stat::make('Total', $budget->balance())
                ->color('gray'),
            Stat::make('Expenses', $budget->expensesSum())
                ->chart($budget->expensesPerDay())
                ->color('danger'),
        ];
    }
}
