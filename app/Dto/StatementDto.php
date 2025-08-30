<?php

namespace App\Dto;

use App\Types\Money;
use Illuminate\Support\Collection;

readonly class StatementDto
{
    public function __construct(
        public string $name,
        public Money $balance,
        public Collection $children,
        public Collection $accounts,
    ) {}
}
