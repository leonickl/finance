<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Helpers\CurrencyAttribute;
use App\Types\Currency;
use App\Types\Money;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;

/**
 * @property int $id
 * @property string $bank
 * @property int $account_id
 * @property float|null $balance
 * @property Currency $currency
 * @property-read Account $account
 * @property-read Collection<int, BankTransaction> $bankTransactions
 */
final class BankAccount extends Model
{
    use CurrencyAttribute;

    public function casts(): array
    {
        return ['balance' => 'float'];
    }

    public function balance(): ?Money
    {
        return $this->balance === null ? null : Money::new($this->balance, $this->currency);
    }

    public function label(): string
    {
        return $this->bank.' - '.$this->account->name;
    }

    public function toArray()
    {
        return [
            'id' => $this->id,
            'bank' => $this->bank,
            'account' => $this->account->toArray(),
            'balance' => $this->balance(),
        ];
    }

    protected function account(): BelongsTo
    {
        return $this->belongsTo(Account::class, 'account_id');
    }

    protected function bankTransactions(): HasMany
    {
        return $this->hasMany(BankTransaction::class, 'bank_account_id');
    }
}
