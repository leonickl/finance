<?php

declare(strict_types=1);

namespace App\Types;

final class Number
{
    public static function floatFromGerman(string $string): float
    {
        return (float) str_replace(
            ',',
            '.',
            str_replace('.', '', $string),
        );
    }

    public static function percentage(float $number): string
    {
        return round($number * 100, 2).' %';
    }
}
