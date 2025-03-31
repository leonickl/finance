<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Helpers\CurrencyAttribute;
use App\Types\Date\Date;
use App\Types\Money;
use App\Types\TransactionCollection;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Override;

/**
 * @property int $id
 * @property int $debit_id
 * @property int $credit_id
 * @property float $value
 * @property string $text
 * @property Carbon $timestamp
 * @property int|null $claim_id
 * @property string|null $group_uid
 * @property int|null $person_id
 * @property-read Date $date
 * @property-read Account $debit
 * @property-read Account $credit
 * @property-read Person $person
 * @property-read Transaction $claim
 * @property-read Collection<int, BankTransaction> $bankTransactions
 */
final class Transaction extends Model
{
    use CurrencyAttribute;

    protected function casts(): array
    {
        return [
            'value' => 'float',
            'timestamp' => 'datetime',
        ];
    }

    #[Override]
    public static function all($columns = ['*']): TransactionCollection
    {
        return TransactionCollection::make([...parent::all()]);
    }

    public static function create(
        Account $debit,
        Account $credit,
        Money $value,
        string $text = '',
        ?Date $date = null,
        ?string $groupUid = null,
        ?Transaction $claim = null,
        ?Person $person = null,
    ): self {
        $transaction = new Transaction;

        $transaction->debit_id = $debit->id;
        $transaction->credit_id = $credit->id;
        $transaction->value = $value->float();
        $transaction->currency = $value->currency();
        $transaction->text = $text;
        $transaction->group_uid = $groupUid;
        $transaction->date = $date ?? Date::now();
        $transaction->claim_id = $claim?->id;
        $transaction->person_id = $person?->id;

        $transaction->save();

        return $transaction;
    }

    public function toArray(): array
    {
        return [
            ...parent::toArray(),
            'debit' => $this->debit,
            'credit' => $this->credit,
            'claim' => $this->claim,
            'person' => $this->person,
            'currency' => $this->currency->toArray(),
        ];
    }

    protected function text(): Attribute
    {
        $callback = fn (string $text) => trim(str_replace(['<br>', '<br/>', '<br />'], [' ', ' ', ' '], $text));

        return Attribute::make(get: $callback, set: $callback);
    }

    protected function date(): Attribute
    {
        return Attribute::make(
            get: fn () => Date::of($this->timestamp),
            set: fn (Date $date) => [
                'timestamp' => $date->carbon(),
            ]
        );
    }

    public function isSplitTransaction(): bool
    {
        return $this->debit_id === Account::splitTransactions()->id
            || $this->credit_id === Account::splitTransactions()->id;
    }

    public function value(): Money
    {
        return Money::new($this->value, $this->currency);
    }

    protected function debit(): BelongsTo
    {
        return $this->belongsTo(Account::class, 'debit_id');
    }

    protected function credit(): BelongsTo
    {
        return $this->belongsTo(Account::class, 'credit_id');
    }

    protected function claim(): BelongsTo
    {
        return $this->belongsTo(Transaction::class, 'claim_id');
    }

    protected function person(): BelongsTo
    {
        return $this->belongsTo(Person::class);
    }

    protected function bankTransactions(): HasMany
    {
        return $this->hasMany(BankTransaction::class);
    }
}
