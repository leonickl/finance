<?php

declare(strict_types=1);

namespace App\Statistics\Charts;

use App\Models\Account;
use App\Types\AccountType;
use App\Types\DebitCredit;
use Illuminate\Support\Collection;
use Override;

final readonly class IncomeBudgetLines extends BudgetLines
{
    #[Override]
    protected function accounts(): Collection
    {
        return Account::where('type', AccountType::INCOME->value)->get();
    }

    #[Override]
    protected function sortBy(Account $account): float
    {
        return $account->creditTransactions()
            ->afterCarbon(now()->subMonths(3))
            ->sumValues()
            ->float();
    }

    #[Override]
    protected function side(): DebitCredit
    {
        return DebitCredit::CREDIT;
    }
}
