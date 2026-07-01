<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use App\Statistics\Charts\IncomeBudgetLines;
use App\Statistics\Charts\Lines;
use Filament\Widgets\ChartWidget;

final class IncomeBudgetLinesChart extends ChartWidget
{
    protected ?string $heading = 'Income Budget Lines';

    protected ?array $options = [
        'aspectRatio' => .5,
    ];

    protected function getData(): array
    {
        return (new IncomeBudgetLines(Lines::range()))->chartData();
    }

    protected function getType(): string
    {
        return 'line';
    }
}
