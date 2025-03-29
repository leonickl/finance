<?php

declare(strict_types=1);

namespace App\Types\Date;

use Carbon\Carbon;
use Override;

final readonly class Year implements DateUnit
{
    public const string PATTERN = '^\d{4}$';

    private function __construct(private int $year) {}

    public static function now(): Year
    {
        return Year::make((int) date('Y'));
    }

    public static function make(int $year): Year
    {
        return new Year($year);
    }

    public function int(): int
    {
        return $this->year;
    }

    public function toCarbon(): Carbon
    {
        return Carbon::createFromDate(year: $this->year);
    }

    #[Override]
    public function string(): string
    {
        return (string) $this->year;
    }

    #[Override]
    public function minus(int $amount = 1): static
    {
        return Year::make($this->year - $amount);
    }

    #[Override]
    public function float(): float
    {
        return $this->year;
    }

    #[Override]
    public function plus(int $amount = 1): static
    {
        return new Year($this->year + $amount);
    }

    #[Override]
    public function urlParams(): array
    {
        return ['year' => $this->year];
    }
}
