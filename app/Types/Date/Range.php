<?php

declare(strict_types=1);

namespace App\Types\Date;

use App\Statistics\Charts\Point;
use App\Statistics\Charts\Points;
use App\Statistics\Regression;
use App\Types\TransactionCollection;
use Illuminate\Support\Collection;

abstract readonly class Range
{
    public function labels(): Collection
    {
        return $this->elements()->map(fn (DateUnit $xValue) => $xValue->string());
    }

    abstract public function group(TransactionCollection $transactions): Points;

    abstract public function elements(): Collection;

    public function generateRegressionPoints(Range $range, Regression $reg): Points
    {
        $points = Points::make();

        $range->elements()->each(fn (DateUnit $x) => $points->add(new Point($x, $reg->predict($x->float()))));

        return $points;
    }

    abstract public function extend(int $plus): static;

    public function count(): int
    {
        return $this->elements()->count();
    }
}
