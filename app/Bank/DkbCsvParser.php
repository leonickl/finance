<?php

declare(strict_types=1);

namespace App\Bank;

use App\Types\Currency;
use App\Types\Date\Date;
use App\Types\Money;
use App\Types\Number;
use Illuminate\Support\Collection;
use Override;

final readonly class DkbCsvParser extends CsvParser
{
    public function __construct(int $bankAccountId)
    {
        parent::__construct(
            bankAccountId: $bankAccountId,
            options: new CsvParserOptions(
                balanceLine: 2,
                balanceCell: 1,
                transactionsStartAtLine: 5,
                encoding: 'windows-1252',
                lineSeparator: "\n",
                cellSeparator: ';',
            ),
        );
    }

    #[Override]
    public function arrayToTransaction(array $transaction): ?BankTransactionDto
    {
        if ($transaction[2] === 'Vorgemerkt') {
            return null; // do not use unbooked transactions
        }

        return new BankTransactionDto(
            date: Date::fromShortGermanDate($transaction[0]),
            text: $transaction[4],
            value: Money::new(
                Number::floatFromGerman($transaction[8]),
                Currency::new('EUR'),
            ),
            iban: $transaction[7],
            bankAccountId: $this->bankAccountId,
        );
    }

    #[Override]
    public function findBalance(Collection $lines): Money
    {
        $balanceCell = $lines[$this->options->balanceLine][$this->options->balanceCell];

        [$balanceValue, $balanceCurrency] = explode(' ', $balanceCell);

        return Money::new(
            Number::floatFromGerman($balanceValue),
            Currency::new($balanceCurrency),
        );
    }
}
