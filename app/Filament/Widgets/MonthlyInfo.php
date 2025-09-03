<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use App\Statistics\Budget\MonthlyBudget;
use App\Types\Date\Month;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

final class MonthlyInfo extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        $budget = new MonthlyBudget(Month::now()->plus(session('lag', 0)));

        return [
            Stat::make('Monthly Income', $budget->incomeSum())
                ->chart($budget->incomePerDay())
                ->color('success'),
            Stat::make('Monthly Balance', $budget->balance())
                ->color('gray'),
            Stat::make('Monthly Expenses', $budget->expensesSum())
                ->chart($budget->expensesPerDay())
                ->color('danger'),
        ];
    }
}
