<?php

declare(strict_types=1);

namespace App\Bank;

final readonly class CsvParserOptions
{
    /**
     * numbers are zero-based
     */
    public function __construct(
        public ?int $balanceLine = null,
        public ?int $balanceCell = null,
        public ?int $balanceCurrencyCell = null,
        public int $transactionsStartAtLine = 1,
        public string $encoding = 'utf-8',
        public string $lineSeparator = "\n",
        public string $cellSeparator = ';',
        public string $trimLine = '',
    ) {}
}
