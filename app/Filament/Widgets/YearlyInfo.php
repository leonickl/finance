<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use App\Statistics\Budget\YearlyBudget;
use App\Types\Date\Year;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

final class YearlyInfo extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        $budget = new YearlyBudget(Year::now()->plus(session('lag', 0)));

        return [
            Stat::make('Yearly Income', $budget->incomeSum())
                ->chart($budget->incomePerMonth())
                ->color('success'),
            Stat::make('Yearly Balance', $budget->balance())
                ->color('gray'),
            Stat::make('Yearly Expenses', $budget->expensesSum())
                ->chart($budget->expensesPerMonth())
                ->color('danger'),
        ];
    }
}
