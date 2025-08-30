<?php

namespace App\Statistics\Charts;

use App\Types\Date\Month;
use App\Types\Date\MonthRange;

class Lines
{
    public static function range()
    {
        return new MonthRange(Month::now()->minus(12 * 3), Month::now());
    }
}
