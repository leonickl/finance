<?php

declare(strict_types=1);

namespace App\Types\Date;

use Carbon\CarbonPeriod;
use Illuminate\Support\Collection;
use Override;

final readonly class YearRange extends Range
{
    public function __construct(private Year $start, private Year $end, private int $gap = 1) {}

    #[Override]
    public function elements(): Collection
    {
        $carbonPeriod = CarbonPeriod::create(
            $this->start->toCarbon(),
            $this->gap.' year',
            $this->end->toCarbon(),
        );

        $years = [];

        foreach ($carbonPeriod as $carbon) {
            $years[] = Year::make($carbon->year);
        }

        return collect($years);
    }

    #[Override]
    public function extend(int $plus): static
    {
        return new YearRange($this->start, $this->end->plus($plus));
    }
}
