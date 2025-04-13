<?php

declare(strict_types=1);

namespace App\Bank;

use App\Bank\BankTransactionDto;
use App\Bank\CsvParserOptions;
use App\Bank\ParserResult;
use App\Types\Currency;
use App\Types\Money;
use App\Types\Number;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

abstract readonly class CsvParser implements Parser
{
    public function __construct(protected int $bankAccountId, protected CsvParserOptions $options) {}

    public function parse(string $data): ParserResult
    {
        $lines = $this->lines($data);

        return new ParserResult(
            balance: $this->findBalance($lines),
            transactions: $this->transactions($lines),
        );
    }

    /**
     * @return Collection<int, string[]>
     */
    public function lines(string $csvContent): Collection
    {
        return collect(explode($this->options->lineSeparator, $csvContent))->map(
            fn (string $line) => Arr::map(
                explode($this->options->cellSeparator, $line),
                fn (string $cell) => CsvParser::trimQuotes($cell),
            ),
        );
    }

    protected static function trimQuotes(string $string): string
    {
        return trim($string, '"');
    }

    /**
     * @param  Collection<int, string[]>  $lines
     */
    public function findBalance(Collection $lines): ?Money
    {
        return Money::new(
            Number::floatFromGerman($lines[$this->options->balanceLine][$this->options->balanceCell]),
            Currency::new($lines[$this->options->balanceLine][$this->options->balanceCurrencyCell]),
        );
    }

    /**
     * @param  Collection<int, string[]>  $lines
     * @return Collection<int, BankTransactionDto>
     */
    public function transactions(Collection $lines): Collection
    {
        return $lines
            ->slice($this->options->transactionsStartAtLine)
            ->map(fn (array $transaction) => $this->arrayToTransaction($transaction))
            ->filter()
            ->filter(fn (BankTransactionDto $transaction) => ! $transaction->isAlreadySaved());
    }

    /**
     * @param  string[]  $transaction
     */
    abstract public function arrayToTransaction(array $transaction): ?BankTransactionDto;
}
