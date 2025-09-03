<?php

declare(strict_types=1);

namespace App\Statistics\Charts;

use App\Types\Date\DateUnit;
use App\Types\Floatable;

final readonly class Point
{
    private DateUnit $x;

    private float $y;

    public function __construct(DateUnit $x, float | Floatable $y)
    {
        $this->x = $x;
        $this->y = is_float($y) ? $y : $y->float();
    }

    public function toArray(): array
    {
        return ['x' => $this->x->string(), 'y' => $this->y];
    }

    public function x(): DateUnit
    {
        return $this->x;
    }

    public function y(): float
    {
        return $this->y;
    }
}
