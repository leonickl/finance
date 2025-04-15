<?php

declare(strict_types=1);

namespace App\Bank;

// use this script to scrape data from the web interface:
// JSON.stringify([...document.querySelectorAll('.timeline__entry:not(.-isNewSection)')].map(entry => ({text: entry.querySelector('.timelineV2Event__title')?.outerText,date: entry.querySelector('.timelineV2Event__subtitle')?.outerText?.split(' - ')?.[0],value: entry.querySelector('.timelineV2Event__price p')?.outerText,})))
// Because TR does not show years in its logs, the parse method adds them itself.
// A new year is always started if the numeric month is less than the previous (e.g. change from Dec to Jan).
// Could fail if there are no transactions for a year. Then the year will not be incremented automatically.

use App\Types\Currency;
use App\Types\Date\Date;
use App\Types\Money;
use App\Types\Number;
use Exception;
use Override;
use RuntimeException;
use stdClass;

final readonly class TradeRepublicParser implements Parser
{
    public function __construct(private int $bankAccountId) {}

    #[Override]
    public function parse(string $data): ParserResult
    {
        $transactions = collect(json_decode($data));

        $startYear = date('Y');
        $monthBefore = null;

        foreach ($transactions as $transaction) {
            $month = explode('.', $transaction->date)[1];

            if ($month < 1 || $month > 12) {
                throw new RuntimeException('invalid month: '.$month);
            }

            // when processing the list, dates get smaller (parser enters the past)
            // if a past date has a greater month than the previous, the year is changed
            if ($monthBefore !== null && $month > $monthBefore) {
                $startYear--;
            }

            $monthBefore = $month;

            $transaction->date .= $startYear;
        }

        return new ParserResult(
            balance: null,
            transactions: $transactions
                ->map(fn (stdClass $object) => $this->objectToTransaction($object))
                ->filter(),
        );
    }

    private function objectToTransaction(stdClass $object): ?BankTransactionDto
    {
        if (!isset($object->value)) {
            // e. g., credit card verification
            return null;
        }

        if (! isset($object->date, $object->text)) {
            throw new RuntimeException('Invalid data object');
        }

        [$value, $currency] = explode(' ', $object->value);

        // trade republic prefixes only positive numbers, negative numbers have no sign.
        if ($value[0] !== '+') {
            $value = '-'.$value;
        }

        if ($currency !== '€') {
            throw new Exception('Only euro supported in '.self::class.', given '.$currency);
        }

        return new BankTransactionDto(
            date: Date::fromGermanDate($object->date),
            text: $object->text,
            value: Money::new(Number::floatFromGerman($value), Currency::new('EUR')),
            iban: null,
            bankAccountId: $this->bankAccountId,
        );
    }
}
