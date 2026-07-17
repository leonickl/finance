<?php

declare(strict_types=1);

namespace App\Filament\Pages;

use App\Models\Streak;
use App\Models\Transaction;
use App\Types\Date\Month;
use App\Types\Date\MonthRange;
use Filament\Pages\Page;

final class StreakStatus extends Page
{
    protected string $view = 'filament.pages.streak-status';

    public array $streaks = [];

    public function mount(): void
    {
        $this->streaks = $this->computeStreakStatus();
    }

    public static function getNavigationIcon(): string
    {
        return 'heroicon-o-arrow-path';
    }

    public function getTitle(): string
    {
        return 'Streak Status';
    }

    private function computeStreakStatus(): array
    {
        $streaks = Streak::with(['debit', 'credit'])->get();
        $result = [];

        foreach ($streaks as $streak) {
            $range = new MonthRange(
                start: $streak->first,
                end: $streak->last ?? Month::now()->plus(3),
            );

            $years = [];

            foreach ($range->elements() as $month) {
                $transactions = Transaction::query()
                    ->where('debit_id', $streak->debit_id)
                    ->where('credit_id', $streak->credit_id)
                    ->where('value', $streak->value)
                    ->whereYear('timestamp', $month->year())
                    ->whereMonth('timestamp', $month->month())
                    ->get();

                $years[$month->year()][] = [
                    'month' => $month->month(),
                    'count' => $transactions->count(),
                    'transaction_ids' => $transactions->pluck('id')->all(),
                ];
            }

            $result[] = [
                'id' => $streak->id,
                'name' => $streak->name,
                'value' => $streak->value,
                'currency' => $streak->currency->code(),
                'day' => $streak->day,
                'debit_name' => $streak->debit->name,
                'credit_name' => $streak->credit->name,
                'first' => $streak->first->string(),
                'last' => $streak->last?->string(),
                'years' => $years,
            ];
        }

        return $result;
    }
}
