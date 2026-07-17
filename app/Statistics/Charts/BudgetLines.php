<?php

declare(strict_types=1);

namespace App\Statistics\Charts;

use App\Models\Account;
use App\Types\Date\MonthRange;
use App\Types\Date\Range;
use App\Types\Date\YearRange;
use App\Types\DebitCredit;
use App\Types\Map;
use Illuminate\Support\Collection;
use RuntimeException;

abstract readonly class BudgetLines
{
    public function __construct(
        protected Range $range,
        protected bool $cumulative = false,
    ) {}

    public function chartData(): array
    {
        return [
            'labels' => $this->labels(),
            'datasets' => $this->datasets(),
        ];
    }

    private function labels(): array
    {
        return $this->range->labels()->toArray();
    }

    abstract protected function accounts(): Collection;

    abstract protected function sortBy(Account $account): float;

    private function datasets(): array
    {
        // month could also be year here (DateUnit)

        $months = $this->range->elements()->toArray();
        $zeroes = array_map(fn () => 0, $months);

        $months = Map::combine($months, $zeroes);

        $accounts = $this->accounts()->pluck('id')->toArray();
        // create a copy of the months array for each account
        $monthLists = array_map(fn () => clone $months, $accounts); // maybe clone $months here
        $accounts = array_combine($accounts, $monthLists);

        $accountNames = $this->accounts()->mapWithKeys(fn (Account $account) => [$account->id => $account->fullname]);

        foreach ($this->accounts() as $account) {
            foreach ($account->transactions() as $transaction) {
                $dateUnitType = match (true) {
                    $this->range instanceof MonthRange => 'month',
                    $this->range instanceof YearRange => 'year',
                    default => throw new RuntimeException('Unknown Date Unit ' . $this->range::class)
                };

                $month = $transaction->date->{$dateUnitType}();

                // if month is included in current range
                if ($accounts[$account->id]->has($month)) {
                    $accounts[$account->id][$month] = $accounts[$account->id][$month] + $transaction->value()->float();
                }
            }
        }

        $sorted = collect($accounts)
            ->map(fn (Map $balanceByMonths, int $accountId) => [
                'name' => $accountNames[$accountId],
                'data' => $balanceByMonths,
            ])
            ->sortByDesc(fn (array $item) => array_sum($item['data']->values()))
            ->values();

        if ($this->cumulative) {
            $stacked = [];
            $runningTotals = null;

            foreach ($sorted as $item) {
                if ($runningTotals === null) {
                    $runningTotals = clone $item['data'];
                    $stacked[] = $item;
                } else {
                    $stackedMap = Map::empty();

                    foreach ($item['data'] as [$month, $value]) {
                        $stackedMap[$month] = $runningTotals[$month] + $value;
                    }

                    $runningTotals = $stackedMap;
                    $stacked[] = ['name' => $item['name'], 'data' => $stackedMap];
                }
            }

            $sorted = collect($stacked);
        }

        return $sorted
            ->map(fn (array $item) => new IncomeExpenseChartDataset(
                accountName: $item['name'],
                balanceByMonths: $item['data'],
            ))
            ->map(fn (ChartDataset $chartDataset) => $chartDataset->toArray())
            ->toArray();
    }

    abstract protected function side(): DebitCredit;
}
