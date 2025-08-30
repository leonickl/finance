<?php

declare(strict_types=1);

namespace App\Types;

enum DebitCredit: string
{
    case DEBIT = 'debit';
    case CREDIT = 'credit';

    public function databaseColumn(): string
    {
        return match ($this) {
            self::DEBIT => 'debit_id',
            self::CREDIT => 'credit_id',
        };
    }

    public function other(): DebitCredit
    {
        return match ($this) {
            self::DEBIT => self::CREDIT,
            self::CREDIT => self::DEBIT,
        };
    }

    public function equals(self $other): bool
    {
        return $this->value === $other->value;
    }
}
