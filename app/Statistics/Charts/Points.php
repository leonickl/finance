<?php

declare(strict_types=1);

namespace App\Statistics\Charts;

use App\Types\Map;
use Illuminate\Support\Collection;

final readonly class Points
{
    private Collection $points;

    private function __construct()
    {
        $this->points = Collection::empty();
    }

    public static function fromMap(Map $map): self
    {
        $points = self::make();

        foreach ($map as [$month, $value]) {
            $points->add(new Point($month, $value));
        }

        return $points;
    }

    public function max(): float
    {
        return $this->points->max(fn (Point $point) => $point->y());
    }

    public static function make(): Points
    {
        return new self;
    }

    public static function fromCollection(Collection $points): Points
    {
        $instance = self::make();

        $points->each(fn (Point $point) => $instance->add($point));

        return $instance;
    }

    public function add(Point $point): Points
    {
        $this->points->push($point);

        return $this;
    }

    public function points(): array
    {
        return $this->points->toArray();
    }

    public function toArray(): array
    {
        return $this->points
            ->map(fn (Point $point) => $point->toArray())
            ->values()
            ->toArray();
    }

    public function x(): Collection
    {
        return collect($this->points)->map(fn (Point $point) => $point->x()->float());
    }

    public function y(): Collection
    {
        return collect($this->points)->map(fn (Point $point) => $point->y());
    }

    public function borders(): Points
    {
        $points = Points::make();

        $points->add($this->points->first());
        $points->add($this->points->last());

        return $points;
    }
}
