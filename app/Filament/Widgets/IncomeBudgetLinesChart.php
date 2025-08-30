<?php

namespace App\Filament\Widgets;

use App\Statistics\Charts\IncomeBudgetLines;
use App\Statistics\Charts\Lines;
use Filament\Widgets\ChartWidget;

class IncomeBudgetLinesChart extends ChartWidget
{
    protected ?string $heading = 'Income Budget Lines';

    protected function getData(): array
    {
        return (new IncomeBudgetLines(Lines::range()))->chartData();
    }

    protected function getType(): string
    {
        return 'line';
    }
}
