<?php

declare(strict_types=1);

namespace App\Statistics\Charts;

use App\Types\Color;

abstract readonly class ChartDataset
{
    public function __construct(
        protected string $label,
        protected ?string $borderColor = null,
    ) {}

    public function toArray(): array
    {
        return [
            'label' => $this->label,
            'fill' => false,
            'borderColor' => $this->borderColor ?? Color::forChart($this->label),
            'backgroundColor' => 'lightgray',
            'tension' => .6,
            'data' => $this->data()->toArray(),
        ];
    }

    abstract protected function data(): Points;

    public function max(): float
    {
        return $this->data()->max();
    }

    public function size(): float
    {
        return once(fn () => $this->data()->y()->sum());
    }
}
