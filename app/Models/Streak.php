<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Helpers\CurrencyAttribute;
use App\Types\Date\Date;
use App\Types\Date\Month;
use App\Types\Date\MonthRange;
use App\Types\Money;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $day
 * @property Month $first
 * @property Month|null $last
 * @property string $name
 * @property float $value
 * @property int $debit_id
 * @property int $credit_id
 * @property-read Account $debit
 * @property-read Account $credit
 */
final class Streak extends Model
{
    use CurrencyAttribute;

    protected function casts(): array
    {
        return ['value' => 'float'];
    }

    public function money(): Money
    {
        return Money::new($this->value, $this->currency);
    }

    public function createMissing(): void
    {
        $range = new MonthRange(
            start: $this->first,
            end: $this->last ?? Month::now()->plus(3),
        );

        foreach ($range->elements() as $month) {
            $transactions = Transaction::query()
                ->where('debit_id', $this->debit_id)
                ->where('credit_id', $this->credit_id)
                ->where('value', $this->value)
                ->whereBetween('timestamp', [$month->toCarbon()->startOfMonth(), $month->toCarbon()->endOfMonth()])
                ->first();

            if ($transactions) {
                continue;
            }

            Transaction::create(
                debit: $this->debit,
                credit: $this->credit,
                value: Money::new($this->value, $this->currency),
                text: $this->name,
                date: Date::of(Carbon::create(
                    year: $month->year(),
                    month: $month->month(),
                    day: $this->day,
                )),
            );

        }
    }

    protected function first(): Attribute
    {
        return Attribute::make(
            get: fn (string $month) => Month::fromCarbon(Carbon::make($month)),
            set: fn (Month $month) => $month->toCarbon(),
        );
    }

    protected function last(): Attribute
    {
        return Attribute::make(
            get: fn (?string $month) => $month === null ? null : Month::fromCarbon(Carbon::make($month)),
            set: fn (?Month $month) => $month?->toCarbon(),
        );
    }

    public function debit(): BelongsTo
    {
        return $this->belongsTo(Account::class, 'debit_id');
    }

    public function credit(): BelongsTo
    {
        return $this->belongsTo(Account::class, 'credit_id');
    }
}
