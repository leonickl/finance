<?php

declare(strict_types=1);

namespace App\Statistics\Charts;

use App\Statistics\Regression;
use App\Types\Date\MonthRange;
use App\Types\TransactionCollection;

final readonly class LineChartInOutTotal extends LineChart
{
    public static function make(MonthRange $range, int $horizon = 0): self
    {
        return new self($range, $horizon, self::datasets($range, $horizon));
    }

    private static function datasets(MonthRange $range, int $predictionDuration): array
    {
        $incomePoints = $range->group(TransactionCollection::allIncomes());
        $expensePoints = $range->group(TransactionCollection::allExpenses());
        $balancePoints = self::balancePoints($incomePoints, $expensePoints);

        $incomeRegPoints = self::regression($incomePoints, $range, $predictionDuration);
        $expenseRegPoints = self::regression($expensePoints, $range, $predictionDuration);
        $balanceRegPoints = self::regression($balancePoints, $range, $predictionDuration);

        return [
            new SummaryChartDataset(
                label: 'expenses',
                data: $expensePoints,
                borderColor: 'red',
            ),
            new SummaryChartDataset(
                label: 'income',
                data: $incomePoints,
                borderColor: 'green',
            ),
            new SummaryChartDataset(
                label: 'balance',
                data: $balancePoints,
                borderColor: 'gray',
            ),
            new SummaryChartDataset(
                label: 'prognosis',
                data: $expenseRegPoints,
                borderColor: '#ff6363',
            ),
            new SummaryChartDataset(
                label: 'prognosis',
                data: $incomeRegPoints,
                borderColor: '#61c561',
            ),
            new SummaryChartDataset(
                label: 'prognosis',
                data: $balanceRegPoints,
                borderColor: 'lightgray',
            ),
        ];
    }

    private static function balancePoints(Points $incomePoints, Points $expensePoints): Points
    {
        $data = array_map(
            fn (Point $income, Point $expenses) => new Point($income->x(), $income->y() - $expenses->y()),
            $incomePoints->points(),
            $expensePoints->points(),
        );

        return Points::fromCollection(collect($data));
    }

    private static function regression(Points $points, MonthRange $range, int $predictionDuration): Points
    {
        return $range->generateRegressionPoints($range->extend($predictionDuration), new Regression($points))->borders();
    }
}
