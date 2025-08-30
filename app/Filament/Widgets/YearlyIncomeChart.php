<?php

namespace App\Filament\Widgets;

use App\Statistics\Budget\YearlyBudget;
use App\Types\Date\Year;
use Filament\Widgets\ChartWidget;

class YearlyIncomeChart extends ChartWidget
{
    protected ?string $heading = 'Yearly Income';

    protected function getData(): array
    {
        return (new YearlyBudget(Year::now()->plus(session('lag'))))->incomeDonutData();
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
}
