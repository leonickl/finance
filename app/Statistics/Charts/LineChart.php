<?php

declare(strict_types=1);

namespace App\Statistics\Charts;

use App\Types\Date\Range;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Str;
use Override;

abstract readonly class LineChart implements Htmlable
{
    protected function __construct(
        private Range $range,
        private int $predictionDuration = 0,
        private array $datasets = [],
        private string $width = '100%',
        private string $height = '100%',
    ) {}

    public function chartData()
    {
        return [
            'labels' => $this->range->extend($this->predictionDuration)->labels()->toArray(),
            'datasets' => collect($this->datasets)->map(fn (ChartDataset $dataset) => $dataset->toArray()),
        ];
    }

    public function dump(): self
    {
        dump($this->datasets);

        return $this;
    }
}
