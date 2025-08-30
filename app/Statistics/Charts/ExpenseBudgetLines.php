<?php

declare(strict_types=1);

namespace App\Statistics\Charts;

use App\Models\Account;
use App\Types\AccountType;
use App\Types\DebitCredit;
use Illuminate\Support\Collection;
use Override;

final readonly class ExpenseBudgetLines extends BudgetLines
{
    #[Override]
    protected function accounts(): Collection
    {
        return Account::where('group_id', AccountType::EXPENSES->value)->get();
    }

    #[Override]
    protected function sortBy(Account $account): float
    {
        return $account->debitTransactions()
            ->afterCarbon(now()->subMonths(3))
            ->sumValues()
            ->float();
    }

    protected function side(): DebitCredit
    {
        return DebitCredit::DEBIT;
    }
}
