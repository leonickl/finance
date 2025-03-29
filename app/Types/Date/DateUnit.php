<?php

declare(strict_types=1);

namespace App\Types\Date;

interface DateUnit
{
    public function string(): string;

    public function float();

    public function minus(int $amount = 1): static;

    public function plus(int $amount = 1): static;

    public function urlParams(): array;
}
