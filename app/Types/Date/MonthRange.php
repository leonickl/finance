<?php

declare(strict_types=1);

namespace App\Types\Date;

use Carbon\CarbonPeriod;
use Illuminate\Support\Collection;
use Override;

final readonly class MonthRange extends Range
{
    public function __construct(public Month $start, public Month $end, public int $gap = 1) {}

    public static function fromStrings(string ...$months): self
    {
        sort($months);

        $months = array_map(Month::fromString(...), $months);

        $start = count($months) > 0 ? $months[0] : Month::now()->minus(12);
        $end = count($months) > 0 ? $months[count($months) - 1] : Month::now();

        return new self($start, $end);
    }

    /**
     * @return Collection<int, Month>
     */
    #[Override]
    public function elements(): Collection
    {
        $carbonPeriod = CarbonPeriod::create(
            $this->start->toCarbon(),
            $this->gap.' month',
            $this->end->toCarbon(),
        );

        $months = [];

        foreach ($carbonPeriod as $carbon) {
            $months[] = Month::make($carbon->year, $carbon->month);
        }

        return collect($months);
    }

    #[Override]
    public function extend(int $plus): static
    {
        return new MonthRange($this->start, $this->end->plus($plus));
    }

    public function dump(): self
    {
        dump($this);

        return $this;
    }
}
