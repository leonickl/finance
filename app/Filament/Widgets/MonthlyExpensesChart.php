<?php

namespace App\Filament\Widgets;

use App\Statistics\Budget\MonthlyBudget;
use App\Types\Date\Month;
use Filament\Widgets\ChartWidget;

class MonthlyExpensesChart extends ChartWidget
{
    protected ?string $heading = 'Monthly Expenses Chart';


    protected function getData(): array
    {
        return (new MonthlyBudget(Month::now()->plus(session('lag'))))->expensesDonutData();
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
}
