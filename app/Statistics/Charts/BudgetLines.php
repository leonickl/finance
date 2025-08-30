<?php

declare(strict_types=1);

namespace App\Statistics\Charts;

use App\Models\Account;
use App\Types\Date\MonthRange;
use App\Types\Date\Range;
use App\Types\Date\YearRange;
use App\Types\DebitCredit;
use App\Types\Map;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Collection;
use RuntimeException;

abstract readonly class BudgetLines implements Htmlable
{
    public function __construct(protected Range $range) {}

    private function chartData(): array
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
                    default => throw new RuntimeException('Unknown Date Unit '.$this->range::class)
                };

                $month = $transaction->date()->{$dateUnitType}();

                // if month is included in current range
                if ($accounts[$account->id]->has($month)) {
                    $accounts[$account->id][$month] = $accounts[$account->id][$month] + $transaction->value()->float();
                }
            }
        }

        $datasets = collect();

        foreach ($accounts as $accountId => $balanceByMonths) {
            $datasets[] = new IncomeExpenseChartDataset(accountName: $accountNames[$accountId], balanceByMonths: $balanceByMonths);
        }

        return $datasets
            ->sortBy(fn (ChartDataset $chartDataset) => $chartDataset->size())
            ->values()
            ->map(fn (ChartDataset $chartDataset) => $chartDataset->toArray())
            ->toArray();
    }

    abstract protected function side(): DebitCredit;
}
