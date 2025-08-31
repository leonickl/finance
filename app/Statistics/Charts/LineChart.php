<?php

declare(strict_types=1);

namespace App\Statistics\Charts;

use App\Types\Date\Range;

abstract readonly class LineChart
{
    protected function __construct(
        private Range $range,
        private int $predictionDuration = 0,
        private array $datasets = [],
    ) {}

    public function chartData()
    {
        return [
            'labels' => $this->range->extend($this->predictionDuration)->labels()->toArray(),
            'datasets' => collect($this->datasets)->map(fn (ChartDataset $dataset) => $dataset->toArray()),
        ];
    }
}
