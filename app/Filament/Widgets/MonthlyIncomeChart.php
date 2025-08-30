<?php

namespace App\Filament\Widgets;

use App\Filament\Traits\WithoutLegend;
use App\Statistics\Budget\MonthlyBudget;
use App\Types\Date\Month;
use Filament\Widgets\ChartWidget;

class MonthlyIncomeChart extends ChartWidget
{
    use WithoutLegend;

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
