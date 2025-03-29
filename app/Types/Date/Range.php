<?php

declare(strict_types=1);

namespace App\Types\Date;

use Illuminate\Support\Collection;

abstract readonly class Range
{
    public function labels(): Collection
    {
        return $this->elements()->map(fn (DateUnit $xValue) => $xValue->string());
    }

    abstract public function elements(): Collection;

    abstract public function extend(int $plus): static;

    public function count(): int
    {
        return $this->elements()->count();
    }
}
