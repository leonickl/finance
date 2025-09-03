<?php

declare(strict_types=1);

namespace App\Statistics\Budget;

use App\Models\Transaction;
use App\Types\Date\Year;
use App\Types\TransactionCollection;
use Illuminate\Support\Collection;

final readonly class YearlyBudget extends Budget
{
    public function __construct(Year $year)
    {
        parent::__construct(
            income: TransactionCollection::allIncomes()->allInYear($year),
            expenses: TransactionCollection::allExpenses()->allInYear($year),
        );
    }

    public function expensesPerMonth()
    {
        return $this->expenses()
            ->groupBy(fn (Transaction $record) => $record->date->month())
            ->map(fn (Collection $items) => $items->transactions()->sumValues()->float());
    }

    public function incomePerMonth()
    {
        return $this->income()
            ->groupBy(fn (Transaction $record) => $record->date->month())
            ->map(fn (Collection $items) => $items->transactions()->sumValues()->float());
    }
}
