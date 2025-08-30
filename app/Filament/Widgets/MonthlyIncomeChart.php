<?php

namespace App\Filament\Widgets;

use App\Statistics\Budget\MonthlyBudget;
use App\Types\Date\Month;
use Filament\Widgets\ChartWidget;

class MonthlyIncomeChart extends ChartWidget
{
    protected ?string $heading = 'Monthly Income';

    protected function getData(): array
    {
        return (new MonthlyBudget(Month::now()->plus(session('lag'))))->incomeDonutData();
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
}
