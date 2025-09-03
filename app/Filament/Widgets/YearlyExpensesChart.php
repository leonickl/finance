<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use App\Filament\Traits\WithoutLegend;
use App\Statistics\Budget\YearlyBudget;
use App\Types\Date\Year;
use Filament\Widgets\ChartWidget;

final class YearlyExpensesChart extends ChartWidget
{
    use WithoutLegend;

    protected ?string $heading = 'Yearly Expenses';

    protected function getData(): array
    {
        return (new YearlyBudget(Year::now()->plus(session('lag'))))->expensesDonutData();
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
}
