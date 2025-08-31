<?php

declare(strict_types=1);

namespace App\Statistics\Charts;

final readonly class SummaryChartDataset extends ChartDataset
{
    public function __construct(string $label, private Points $data, ?string $borderColor = null)
    {
        parent::__construct($label, $borderColor);
    }

    protected function data(): Points
    {
        return $this->data;
    }
}
