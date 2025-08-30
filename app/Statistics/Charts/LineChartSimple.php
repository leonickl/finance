<?php

declare(strict_types=1);

namespace App\Statistics\Charts;

use App\Types\Date\Month;
use App\Types\Date\MonthRange;
use App\Types\Map;
use App\Types\Money;
use Illuminate\Support\Collection;

final readonly class LineChartSimple extends LineChart
{
    public static function make(MonthRange $range, Collection $balanceByMonths, bool $withMean): self
    {
        $months = $range->elements()->reverse()->toArray();
        $zeroes = array_map(fn () => Money::zero(), $months);
        $balances = Map::combine($months, $zeroes);

        foreach ($balanceByMonths as $month => $balance) {
            $balances[Month::fromString($month)] = $balance;
        }

        $datasets = [new SummaryChartDataset('Balance', Points::fromMap($balances))];

        if ($withMean) {
            $mean = Money::mean(...$balances->values())->float();

            $datasets[] = new SummaryChartDataset(
                label: 'Mean',
                data: Points::make()
                    ->add(new Point($range->start, $mean))
                    ->add(new Point($range->end, $mean)),
                borderColor: 'red',
            );
        }

        return new self($range, predictionDuration: 0, datasets: $datasets);
    }
}
