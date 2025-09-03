<?php

declare(strict_types=1);

namespace App\Statistics\Charts;

use App\Types\Date\Month;
use App\Types\Date\MonthRange;

final class Lines
{
    public static function range()
    {
        return new MonthRange(Month::now()->minus(12 * 3), Month::now());
    }
}
