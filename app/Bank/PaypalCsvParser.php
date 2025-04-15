<?php

declare(strict_types=1);

namespace App\Bank;

use App\Models\Iban;
use App\Types\Currency;
use App\Types\Date\Date;
use App\Types\Money;
use App\Types\Number;
use Illuminate\Support\Collection;
use Override;

final readonly class PaypalCsvParser extends CsvParser
{
    public function __construct(int $bankAccountId)
    {
        parent::__construct(
            bankAccountId: $bankAccountId,
            options: new CsvParserOptions(
                balanceLine: null,
                balanceCell: null,
                balanceCurrencyCell: null,
                transactionsStartAtLine: 1,
                encoding: 'utf-8',
                lineSeparator: "\n",
                cellSeparator: '","',
                trimLine: '"',
            ),
        );
    }

    #[Override]
    public function findBalance(Collection $lines): null
    {
        return null;
    }

    #[Override]
    public function arrayToTransaction(array $transaction): ?BankTransactionDto
    {
        if (count($transaction) < 13) {
            return null;
        }

        return new BankTransactionDto(
            date: Date::fromGermanDate($transaction[0]),
            text: $transaction[3].'-'.$transaction[4].'-'.$transaction[12],
            value: Money::new(
                Number::floatFromGerman($transaction[9]),
                Currency::new($transaction[6]),
            ),
            iban: Iban::extract($transaction[2]),
            bankAccountId: $this->bankAccountId,
        );
    }
}
