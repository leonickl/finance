<?php

declare(strict_types=1);

namespace App\Types\Date;

use Illuminate\Support\Carbon;
use InvalidArgumentException;
use Override;

final readonly class Month implements DateUnit
{
    public const string PATTERN = '^\d{4}-\d{2}$';

    private function __construct(private int $year, private int $month)
    {
        if ($month < 1 || $month > 12) {
            throw new InvalidArgumentException($this->string().' is not a valid month');
        }
    }

    #[Override]
    public function string(): string
    {
        return $this->year.'-'.($this->month < 10 ? '0'.$this->month : $this->month);
    }

    public static function fromString(string $str): Month
    {
        [$y, $m] = explode('-', $str);

        return self::make((int) $y, (int) $m);
    }

    public static function make(int $year, int $month): Month
    {
        return new Month($year, $month);
    }

    public static function now(): Month
    {
        return self::fromCarbon(Carbon::now());
    }

    public static function fromCarbon(?Carbon $carbon): ?Month
    {
        return $carbon === null ? null : new Month($carbon->year, $carbon->month);
    }

    public function toFloat(): float
    {
        return $this->year + $this->month / 12;
    }

    public function toDate(): Date
    {
        return Date::of($this->toCarbon());
    }

    public function year(): int
    {
        return $this->year;
    }

    public function month(): int
    {
        return $this->month;
    }

    #[Override]
    public function minus(int $amount = 1): static
    {
        return Month::fromCarbon($this->toCarbon()->subMonths($amount));
    }

    public function toCarbon(int $day = 1): Carbon
    {
        return Carbon::createFromDate($this->year, $this->month, $day);
    }

    #[Override]
    public function plus(int $amount = 1): static
    {
        return Month::fromCarbon($this->toCarbon()->addMonths($amount));
    }

    public function equals(?Month $month): bool
    {
        return $month !== null && $this->year === $month->year && $this->month === $month->month;
    }

    public function greaterThan(Month $other): bool
    {
        return $this->toCarbon()->greaterThan($other->toCarbon());
    }

    public function float(): float
    {
        return $this->year + $this->month / 12;
    }

    #[Override]
    public function urlParams(): array
    {
        return ['year' => $this->year, 'month' => $this->month];
    }
}
