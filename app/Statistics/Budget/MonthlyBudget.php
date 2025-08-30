<?php

declare(strict_types=1);

namespace App\Statistics\Budget;

use App\Types\Date\Month;
use App\Types\TransactionCollection;

final readonly class MonthlyBudget extends Budget
{
    public function __construct(Month $month)
    {
        parent::__construct(
            income: TransactionCollection::allIncomes()->allInMonth($month),
            expenses: TransactionCollection::allExpenses()->allInMonth($month),
        );
    }
}
