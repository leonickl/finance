<?php

declare(strict_types=1);

namespace App\Exceptions;

use App\Types\Currency;
use RuntimeException;

final class CurrencyMismatchException extends RuntimeException
{
    public function __construct(Currency $found, Currency $expected)
    {
        parent::__construct('Currency mismatch - found ' . $found->code() . ', expected ' . $expected->code());
    }
}
