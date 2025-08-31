<?php

declare(strict_types=1);

namespace App\Statistics\Charts;

use App\Models\Account;
use App\Types\Money;
use App\Types\TransactionCollection;

final readonly class Grouped
{
    public function __construct(private int $accountId, private TransactionCollection $transactions) {}

    public function balance(): Money
    {
        return $this->transactions->sumValues();
    }

    public function account(): Account
    {
        return Account::findOrFail($this->accountId);
    }
}
