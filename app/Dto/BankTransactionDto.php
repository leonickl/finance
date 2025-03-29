<?php

declare(strict_types=1);

namespace App\Dto;

use App\Exceptions\OnlyEuroSupportedException;
use App\Models\BankAccount;
use App\Models\BankTransaction;
use App\Types\Date\Date;
use App\Types\Money;
use Illuminate\Support\Collection;

final readonly class BankTransactionDto
{
    public function __construct(
        public Date $date,
        public string $text,
        public Money $value,
        public ?string $iban,
        public int $bankAccountId,
    ) {}

    /**
     * @return Collection<int, BankTransactionDto>
     */
    public static function all(int $bankAccountId): Collection
    {
        return BankAccount::findOrDie($bankAccountId)
            ->bankTransactions
            ->map(fn (BankTransaction $transaction) => $transaction->dto());
    }

    /**
     * @throws OnlyEuroSupportedException
     */
    public function tryToTransaction(): ?BankTransaction
    {
        if ($this->isAlreadySaved()) {
            return null;
        }

        //        if ( ! $this->value->currency()->equals(BankAccount::findOrDie($this->bankAccountId)->currency)) {
        //            throw new OnlyEuroSupportedException($this->value->currency()->code(), ' - ' . $this->value->format());
        //        }

        return $this->toTransaction();
    }

    public function isAlreadySaved(): bool
    {
        return BankTransaction::countOccurrences($this) > 0;
    }

    /**
     * Create instance of BankTransaction model that can be saved afterward.
     */
    public function toTransaction(): BankTransaction
    {
        $bankTransaction = new BankTransaction;

        $bankTransaction->bank_account_id = $this->bankAccountId;
        $bankTransaction->date = $this->date->dashedDate();
        $bankTransaction->text = $this->text;
        $bankTransaction->value = $this->value->float();
        $bankTransaction->currency = $this->value->currency();
        $bankTransaction->transaction_id = null;
        $bankTransaction->skipped = false;
        $bankTransaction->src = 'upload';
        $bankTransaction->iban = null;

        return $bankTransaction;
    }
}
