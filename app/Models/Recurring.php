<?php

declare(strict_types=1);

namespace App\Models;

use App\Types\Date\Month;

/**
 * @property int $id
 * @property int $account_id
 * @property int $year
 * @property int $month
 * @property bool $jump
 * @property bool $finished
 */
final class Recurring extends Model
{
    protected function casts(): array
    {
        return [
            'jump' => 'boolean',
            'finished' => 'boolean',
        ];
    }

    public static function new(int $accountId, Month $month): Recurring
    {
        return self::firstOrCreate([
            'account_id' => $accountId,
            'year' => $month->year(),
            'month' => $month->month(),
        ], [
            'jump' => false,
            'finished' => false,
        ]);
    }

    public function color(bool $valueIsOk): string
    {
        if ($this->jump) {
            return 'orange';
        }

        if ($valueIsOk) {
            return 'green';
        }

        if ($this->finished) {
            return 'grey';
        }

        return 'red';
    }
}
