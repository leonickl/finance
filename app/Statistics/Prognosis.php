<?php

declare(strict_types=1);

namespace App\Statistics;

use App\Dto\PrognosisResult;
use App\Types\Date\Month;
use App\Types\TransactionCollection;

final readonly class Prognosis
{
    public const int NUMBER_OF_MONTHS = 12;

    public static function prognosis(Month $prognosisMonth, TransactionCollection $transactions): PrognosisResult
    {
        $sum = 0;
        $factorSum = 0;
        $month = $prognosisMonth->minus();

        $debug = '';

        for ($i = 0; $i < self::NUMBER_OF_MONTHS; $i++) {
            $factor = self::factor($i);

            $factorSum += $factor;

            $values = $transactions
                ->allInMonth(Month::make($month->year(), $month->month()))
                ->sumValues()
                ->float();

            $sum += $factor * $values;

            $debug .= $month->string() . ': '
                . $factor . ' * ' . $values . ' = ' . $factor * $values
                . PHP_EOL;

            $month = $month->minus();
        }

        $sum /= $factorSum;

        return new PrognosisResult(
            debug: $debug,
            factorSum: $factorSum,
            prognosisFor: $prognosisMonth,
            sum: $sum,
        );
    }

    /**
     * month == now -> i = 0 -> factor = 1/2
     * month == now - 1 -> i = 1 -> factor = 1/4
     * month == now - 2 -> i = 2 -> factor = 1/8
     * ...
     */
    private static function factor(int $i): float
    {
        return 1 / (2 ** ($i + 1));
    }
}
