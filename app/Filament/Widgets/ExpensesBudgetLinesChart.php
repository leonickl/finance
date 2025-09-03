<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use App\Statistics\Charts\ExpenseBudgetLines;
use App\Statistics\Charts\Lines;
use Filament\Widgets\ChartWidget;

final class ExpensesBudgetLinesChart extends ChartWidget
{
    protected ?string $heading = 'Expenses Budget Lines';

    protected function getData(): array
    {
        return (new ExpenseBudgetLines(Lines::range()))->chartData();
    }

    protected function getType(): string
    {
        return 'line';
    }
}
