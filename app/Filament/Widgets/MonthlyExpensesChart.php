<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use App\Filament\Traits\WithoutLegend;
use App\Statistics\Budget\MonthlyBudget;
use App\Types\Date\Month;
use Filament\Widgets\ChartWidget;

final class MonthlyExpensesChart extends ChartWidget
{
    use WithoutLegend;

    protected ?string $heading = 'Monthly Expenses';

    protected function getData(): array
    {
        return (new MonthlyBudget(Month::now()->plus(session('lag'))))->expensesDonutData();
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
}
