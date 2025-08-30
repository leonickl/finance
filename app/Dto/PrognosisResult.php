<?php

declare(strict_types=1);

namespace App\Dto;

use App\Types\Date\Month;

final readonly class PrognosisResult
{
    public function __construct(
        public string $debug,
        public float $factorSum,
        public Month $prognosisFor,
        public float $sum,
    ) {}
}
