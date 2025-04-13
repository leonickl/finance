<?php

declare(strict_types=1);

namespace App\Bank;

use App\Models\BankTransaction;
use App\Types\Money;
use Illuminate\Support\Collection;

final readonly class ParserResult
{
    /**
     * @param  Collection<int, BankTransactionDto>  $transactions
     */
    public function __construct(private ?Money $balance, private Collection $transactions) {}

    /**
     * @return Collection<int, BankTransaction>
     */
    public function transactions(): Collection
    {
        return $this->transactions
            ->map(fn (BankTransactionDto $transaction) => $transaction->tryToTransaction())
            ->filter();
    }

    public function balance(): ?Money
    {
        return $this->balance;
    }

    public function dd(): never
    {
        $dumpData = $this->transactions
            ->map(fn (BankTransactionDto $dto) => $dto->toTransaction())
            ->map(fn (BankTransaction $transaction) => [$transaction->text, $transaction->date, $transaction->value]);

        dd($dumpData);
    }
}
