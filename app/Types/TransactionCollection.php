<?php

declare(strict_types=1);

namespace App\Types;

use App\Models\Account;
use App\Models\Transaction;
use App\Types\Date\Month;
use App\Types\Date\Year;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

/**
 * @extends Collection<int, Transaction>
 */
final class TransactionCollection extends Collection
{
    public static function balance(self $debit, self $credit): Money
    {
        return Money::zero(Currency::default())
            ->plus($debit->sumValues())
            ->minus($credit->sumValues());
    }

    public function sumValues(): Money
    {
        return Money::zero(Currency::default())
            ->plusAll(...self::extractValues($this));
    }

    public static function extractValues(Collection $transactions): Collection
    {
        return $transactions->toBase()->map(fn (Transaction $transaction) => $transaction->value());
    }

    public static function allWithAccount(int $accountId, bool $desc = false): self
    {
        $builder = Transaction::where('debit_id', $accountId)
            ->orWhere('credit_id', $accountId);

        if ($desc) {
            $builder->orderByDesc('timestamp');
        }

        return $builder->get()
            ->transactions();
    }

    /**
     * @param  Collection<int, Transaction>  $transactions
     */
    public static function from(Collection $transactions): self
    {
        return self::make([...$transactions]); // @phpstan-ignore-line
    }

    public static function allExpenses(): self
    {
        return self::withDebitIn(
            Account::where('type', AccountType::EXPENSES->value)->pluck('id')->toArray(),
        );
    }

    public static function withDebitIn(array $accountIds): self
    {
        return Transaction::query()
            ->whereIn('debit_id', $accountIds)
            ->get()
            ->transactions();
    }

    public static function allIncomes(): self
    {
        return self::withCreditIn(
            Account::where('type', AccountType::INCOME->value)->pluck('id')->toArray(),
        );
    }

    public static function withCreditIn(array $accountIds): self
    {
        return Transaction::query()
            ->whereIn('credit_id', $accountIds)
            ->get()
            ->transactions();
    }

    public static function allWithDebitAccount(Account $account): self
    {
        return Transaction::where('debit_id', $account->id)->get()->transactions();
    }

    public static function allWithCreditAccount(Account $account): self
    {
        return Transaction::where('credit_id', $account->id)->get()->transactions();
    }

    public static function latest(int $i): TransactionCollection
    {
        return Transaction::query()
            ->orderByDesc('created_at')
            ->limit($i)
            ->get()
            ->transactions();
    }

    public function firstDate(): Carbon
    {
        return $this->map(fn (Transaction $transaction) => $transaction->timestamp)->min() ?? Carbon::now();
    }

    public function orderByValueDesc(): self
    {
        return $this->sortByDesc(fn (Transaction $transaction) => $transaction->value()->float());
    }

    public function allInMonth(Month $month): self
    {
        return $this->filter(fn (Transaction $transaction) => $transaction->timestamp->year === $month->year()
            && $transaction->timestamp->month === $month->month());
    }

    public function withPerson(?int $personId): self
    {
        return $this->filter(fn (Transaction $repayment) => $repayment->person_id === $personId);
    }

    public function withClaimId(?int $claimId): self
    {
        return $this->filter(fn (Transaction $transaction) => $transaction->claim?->id === $claimId);
    }

    public function beforeCarbon(?Carbon $before): self
    {
        return $this->filter(fn (Transaction $transaction) => $before === null || $transaction->timestamp->isBefore($before));
    }

    public function afterCarbon(Carbon $after): self
    {
        return $this->filter(fn (Transaction $transaction) => $after === null || $transaction->timestamp->isBefore($after));
    }

    public function allInYear(Year $year): self
    {
        return $this->filter(fn (Transaction $transaction) => $transaction->timestamp->year === $year->int());
    }

    /** @return Collection<string, Money> */
    public function balanceByMonth(): Collection
    {
        return $this->groupBy(fn (Transaction $transaction) => $transaction->date->month()->string())
            ->map(fn (TransactionCollection $transactions) => $transactions->sumValues());
    }
}
