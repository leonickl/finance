<?php

declare(strict_types=1);

namespace App\Statistics;

use App\Exceptions\DataLengthMismatchException;
use App\Statistics\Charts\Points;

use function count;

final readonly class Regression
{
    private array $arrX;

    private array $arrY;

    private float $sumX;

    private float $sumY;

    private float $sumXX;

    private float $sumYY;

    private float $sumXY;

    private float $meanX;

    private float $meanY;

    private int $n;

    private float $b;

    private float $a;

    public function __construct(Points $data)
    {
        $this->arrX = $data->x()->toArray();
        $this->arrY = $data->y()->toArray();

        if (count($this->arrX) !== count($this->arrY)) {
            throw new DataLengthMismatchException(count($this->arrX), count($this->arrY));
        }

        $this->n = count($this->arrX);

        if ($this->n === 0) {
            $this->a = 0;
            $this->b = 0;

            return;
        }

        if ($this->n === 1) {
            $this->a = $this->arrY[0];
            $this->b = 0;

            return;
        }

        $this->sumX = array_sum($this->arrX);
        $this->sumY = array_sum($this->arrY);

        $this->sumXX = array_sum(array_map(fn ($x) => $x ** 2, $this->arrX));
        $this->sumYY = array_sum(array_map(fn ($y) => $y ** 2, $this->arrY));
        $this->sumXY = array_sum(array_map(fn ($x, $y) => $x * $y, $this->arrX, $this->arrY));

        $this->meanX = $this->sumX / $this->n;
        $this->meanY = $this->sumY / $this->n;

        $this->b = ($this->sumXY - $this->n * $this->meanX * $this->meanY)
            / ($this->sumXX - $this->n * $this->meanX * $this->meanX);
        $this->a = $this->meanY - $this->b * $this->meanX;
    }

    public function predict(int|float $x): float
    {
        return round($this->a + $this->b * $x, 1);
    }

    public function rSquared(): float
    {
        return $this->r() ** 2;
    }

    private function r(): float
    {
        return $this->sumXY * $this->n * $this->meanX * $this->meanY
            / sqrt($this->sumXX * $this->n * $this->meanX * $this->meanX)
            / sqrt($this->sumYY * $this->n * $this->meanY * $this->meanY);
    }
}
