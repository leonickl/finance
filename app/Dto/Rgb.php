<?php

declare(strict_types=1);

namespace App\Dto;

final readonly class Rgb
{
    public function __construct(public int $r, public int $g, public int $b) {}

    public function toHex(): string
    {
        return sprintf('#%02X%02X%02X', $this->r, $this->g, $this->b);
    }
}
