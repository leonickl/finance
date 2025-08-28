<?php

declare(strict_types=1);

namespace App\Models\Helpers;

use App\Types\Currency;
use Illuminate\Database\Eloquent\Casts\Attribute;

/**
 * @property Currency $currency
 */
trait CurrencyAttribute
{
    protected function currency(): Attribute
    {
        return Attribute::make(
            get: fn (string $c) => Currency::new($c),
            set: fn (Currency $c) => $c->code(),
        );
    }
}
