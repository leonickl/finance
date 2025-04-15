<?php

declare(strict_types=1);

namespace App\Bank;

use App\Models\Iban;
use App\Types\Currency;
use App\Types\Date\Date;
use App\Types\Money;
use App\Types\Number;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Override;

final readonly class SpardaCsvParser extends CsvParser
{
    public function __construct(int $bankAccountId)
    {
        parent::__construct(
            bankAccountId: $bankAccountId,
            options: new CsvParserOptions(
                transactionsStartAtLine: 1,
                lineSeparator: "\n",
                cellSeparator: ';',
            ),
        );
    }

    public function findBalance(Collection $lines): null
    {
        return null;
    }

    #[Override]
    public function arrayToTransaction(array $transaction): ?BankTransactionDto
    {
        if (count($transaction) < 6) {
            return null; // not a record
        }

        if (Date::fromGermanDate($transaction[4])->carbon()->lessThan(Carbon::create(2024, 03, 20))) {
            return null;
        }
        // TODO: how to skip unbooked here?

        return new BankTransactionDto(
            date: Date::fromGermanDate($transaction[4]),
            text: $transaction[6].'-'.$transaction[10],
            value: Money::new(
                Number::floatFromGerman($transaction[11]),
                Currency::new($transaction[12]),
            ),
            iban: Iban::extract($transaction[11]),
            bankAccountId: $this->bankAccountId,
        );
    }
}
