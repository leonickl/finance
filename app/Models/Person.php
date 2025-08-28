<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;

/**
 * @property int $id
 * @property string $name
 * @property-read Collection<int, Iban> $ibans
 * @property-read Collection<int, Transaction> $transactions
 */
final class Person extends Model
{
    public static function default(): Person
    {
        return new Person(['name' => '---']);
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }

    public function ibans(): HasMany
    {
        return $this->hasMany(Iban::class);
    }
}
