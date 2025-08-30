<?php

declare(strict_types=1);

namespace App\Statistics\Charts;

use App\Statistics\Regression;
use App\Types\Date\Month;
use App\Types\TransactionCollection;

final readonly class DataPoint
{
    public function __construct(
        private Month $month,
        private ?float $income,
        private ?float $expenses,
    ) {}

    public static function build(
        Month $month,
        Month $stopData,
        TransactionCollection $incomeTransactions,
        TransactionCollection $expenseTransactions,
    ): DataPoint {
        $incomeSum = $month->greaterThan($stopData)
            ? null : $incomeTransactions
                ->allInMonth($month)
                ->sumValues()
                ->float();

        $expensesSum = $month->greaterThan($stopData)
            ? null : $expenseTransactions
                ->allInMonth($month)
                ->sumValues()
                ->float();

        return new DataPoint(
            month: $month,
            income: $incomeSum,
            expenses: $expensesSum,
        );
    }

    public function month(): Month
    {
        return $this->month;
    }

    public function income(): ?float
    {
        return $this->income;
    }

    public function expenses(): ?float
    {
        return $this->expenses;
    }

    public function balance(): ?float
    {
        return $this->hasData()
            ? $this->income - $this->expenses
            : null;
    }

    public function hasData(): bool
    {
        return isset($this->expenses, $this->income);
    }

    public function predict(Regression $regression): float
    {
        return $regression->predict($this->month->toFloat());
    }

    public function incomeCoords(): Point
    {
        return new Point($this->month(), $this->income() ?? 0);
    }

    public function expenseCoords(): Point
    {
        return new Point($this->month(), $this->expenses() ?? 0);
    }

    public function balanceCoords(): Point
    {
        return new Point($this->month(), $this->balance() ?? 0);
    }

    public function regressionCoords(Regression $regression): Point
    {
        return new Point($this->month(), $this->predict($regression) ?? 0);
    }
}
