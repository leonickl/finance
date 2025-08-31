<?php

namespace App\Filament\Widgets;

use App\Statistics\Charts\LineChartInOutTotal;
use App\Statistics\Charts\Lines;
use Filament\Widgets\ChartWidget;

class RegressionChart extends ChartWidget
{
    protected ?string $heading = 'Regression';

    protected function getData(): array
    {
        return LineChartInOutTotal::make(Lines::range(), horizon: 5)->chartData();
    }

    protected function getType(): string
    {
        return 'line';
    }
}
