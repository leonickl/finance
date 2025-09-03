<?php

declare(strict_types=1);

namespace App\Statistics\Budget;

use App\Models\Transaction;
use App\Types\Date\Month;
use App\Types\TransactionCollection;
use Illuminate\Support\Collection;

final readonly class MonthlyBudget extends Budget
{
    public function __construct(Month $month)
    {
        parent::__construct(
            income: TransactionCollection::allIncomes()->allInMonth($month),
            expenses: TransactionCollection::allExpenses()->allInMonth($month),
        );
    }

    public function expensesPerDay()
    {
        return $this->expenses()
            ->groupBy(fn (Transaction $record) => $record->date)
            ->map(fn (Collection $items) => $items->transactions()->sumValues()->float());
    }

    public function incomePerDay()
    {
        return $this->income()
            ->groupBy(fn (Transaction $record) => $record->date)
            ->map(fn (Collection $items) => $items->transactions()->sumValues()->float());
    }
}
