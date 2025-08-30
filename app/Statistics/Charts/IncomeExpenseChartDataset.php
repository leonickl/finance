<?php

declare(strict_types=1);

namespace App\Statistics\Charts;

use App\Types\Map;

final readonly class IncomeExpenseChartDataset extends ChartDataset
{
    public function __construct(
        string $accountName,
        protected Map $balanceByMonths,
        ?string $borderColor = null,
    ) {
        parent::__construct(
            label: $accountName,
            borderColor: $borderColor,
        );
    }

    public function data(): Points
    {
        return Points::fromMap($this->balanceByMonths);
    }
}
