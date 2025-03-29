<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property string $iban
 * @property int|null $person_id
 * @property-read Person $person
 */
final class Iban extends Model
{
    public const IBAN_PATTERN = '/([A-Z]{2}(?: ?[0-9]){18,20})/';

    /**
     * TODO: supports only german ibans right now
     */
    public static function extract(string $text): ?string
    {
        preg_match(self::IBAN_PATTERN, $text, $matches);

        if (count($matches) <= 0) {
            return null;
        }

        $iban = str_replace(' ', '', $matches[0]);

        if (str_starts_with($iban, 'DE')) {
            return mb_substr($iban, 0, 22);
        }

        return null;
    }

    protected function bankTransactions(): HasMany
    {
        return $this->hasMany(BankTransaction::class);
    }

    protected function person(): BelongsTo
    {
        return $this->belongsTo(Person::class);
    }
}
