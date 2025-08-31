<?php

declare(strict_types=1);

namespace App\Statistics\Budget;

use App\Dto\Rgb;
use App\Statistics\Charts\Grouper;
use App\Types\DebitCredit;
use App\Types\Money;
use App\Types\TransactionCollection;

abstract readonly class Budget
{
    public function __construct(
        private TransactionCollection $income,
        private TransactionCollection $expenses,
    ) {}

    public function expensesSum(): Money
    {
        return $this->expenses->sumValues();
    }

    public function incomeSum(): Money
    {
        return $this->income->sumValues();
    }

    public function balance(): Money
    {
        return $this->incomeSum()->minus($this->expensesSum());
    }

    public function incomeGrouper(): Grouper
    {
        return once(fn () => new Grouper($this->income, DebitCredit::CREDIT));
    }

    public function expenseGrouper(): Grouper
    {
        return once(fn () => new Grouper($this->expenses, DebitCredit::DEBIT));
    }

    public function income(): TransactionCollection
    {
        return $this->income;
    }

    public function expenses(): TransactionCollection
    {
        return $this->expenses;
    }

    public function incomeOrdered(): TransactionCollection
    {
        return $this->income()->orderByValueDesc();
    }

    public function expensesOrdered(): TransactionCollection
    {
        return $this->expenses()->orderByValueDesc();
    }

    private static function doughnutData(array $x, array $y, array $colors): array
    {
        return [
            'labels' => array_values($x),
            'datasets' => [
                [
                    'backgroundColor' => $colors,
                    'data' => array_values($y),
                ],
            ],
        ];
    }

    public function incomeDonutData(): array
    {
        return Budget::doughnutData(
            $this->incomeGrouper()->x(),
            $this->incomeGrouper()->y(),
            $this->incomeGrouper()->colors(new Rgb(75, 126, 202)),
        );
    }

    public function expensesDonutData(): array
    {
        return Budget::doughnutData(
            $this->expenseGrouper()->x(),
            $this->expenseGrouper()->y(),
            $this->expenseGrouper()->colors(new Rgb(202, 75, 75)),
        );
    }
}
