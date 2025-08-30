<?php

declare(strict_types=1);

namespace App\Statistics\Budget;

use App\Types\Date\Year;
use App\Types\TransactionCollection;

final readonly class YearlyBudget extends Budget
{
    public function __construct(Year $year)
    {
        parent::__construct(
            income: TransactionCollection::allIncomes()->allInYear($year),
            expenses: TransactionCollection::allExpenses()->allInYear($year),
        );
    }
}
