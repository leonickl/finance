<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Helpers\CurrencyAttribute;
use App\Types\AccountType;
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
 * @property Date $date
 * @property-read Account $debit
 * @property-read Account $credit
 * @property-read Person $person
 * @property-read Transaction $claim
 * @property-read Collection<int, BankTransaction> $bankTransactions
 * @property-read Collection<int, Transaction> $repayments
 * @property-read ?Money $repaid
 * @property-read ?Money $rest
 */
final class Transaction extends Model
{
    use CurrencyAttribute;

    protected function casts(): array
    {
        return [
            'id' => 'int',
            'debit_id' => 'int',
            'credit_id' => 'int',
            'value' => 'float',
            'timestamp' => 'datetime',
            'claim_id' => 'int',
            'person_id' => 'int',
        ];
    }

    #[Override]
    public static function all($columns = ['*']): TransactionCollection
    {
        return TransactionCollection::make([...parent::all($columns)]);
    }

    public static function allClaims($columns = ['*']): TransactionCollection
    {
        $claims = Transaction::whereHas('debit', function ($query) {
            $query->where('group_id', AccountType::CLAIM->value);
        })->get($columns);

        return TransactionCollection::make([...$claims]);
    }

    public static function findForBankTransaction(BankTransaction $bankTransaction): TransactionCollection
    {
        $builder = self::query();

        // check for bank account on correct side
        $builder->where($bankTransaction->value > 0 ? 'debit_id' : 'credit_id', $bankTransaction->bankAccount->account->id);

        // value must match (sign is considered via account side above)
        $builder->where('value', abs($bankTransaction->value));

        // must not have an existing bank transaction with the same sign
        $builder->whereNotIn('id', fn ($query) => $query
            ->select('transaction_id')
            ->from('bank_transactions')
            ->whereNotNull('transaction_id')
            ->where('value', $bankTransaction->value > 0 ? '>' : '<', '0'),
            // TODO check for currency?
        );

        return $builder->get()->transactions();
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
            'id' => $this->id,
            'debit_id' => $this->debit_id,
            'credit_id' => $this->credit_id,
            'value' => $this->value,
            'text' => $this->text,
            'timestamp' => $this->timestamp->toString(),
            'claim_id' => $this->claim_id,
            'group_uid' => $this->group_uid,
            'person_id' => $this->person_id,
            'date' => $this->date,
            'debit' => $this->debit,
            'credit' => $this->credit,
            'person' => $this->person,
            'claim' => $this->claim,
            'currency' => $this->currency->toArray(),
            'money' => $this->value()->toArray(),
            'repaid' => $this->repaid?->toArray(),
            'rest' => $this->rest?->toArray(),
        ];
    }

    public function toSearchableArray(): array
    {
        return [
            'id' => (int) $this->id,
            'debit_id' => (int) $this->debit_id,
            'credit_id' => (int) $this->credit_id,
            'debit' => $this->debit->name,
            'credit' => $this->credit->name,
            'value' => (float) $this->value,
            'text' => $this->text,
            'date' => $this->timestamp,
            'claim_id' => (int) $this->claim_id,
            'group_uid' => $this->group_uid,
            'person' => $this->person?->name,
            'currency' => $this->currency->code(),
        ];
    }

    protected function text(): Attribute
    {
        $callback = fn (?string $text) => trim(str_replace(['<br>', '<br/>', '<br />'], [' ', ' ', ' '], $text ?? ''));

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

    public function hasRepayments(): bool
    {
        return $this->debit->type->isClaimType();
    }

    public function repaid(): Attribute
    {
        return Attribute::get(fn () => $this->hasRepayments() ? $this->repayments->transactions()->sumValues() : null);
    }

    public function rest(): Attribute
    {
        return Attribute::get(fn () => $this->hasRepayments() ? $this->value()->minus($this->repaid) : null);
    }

    public function value(): Money
    {
        return Money::new($this->value, $this->currency);
    }

    public function debit(): BelongsTo
    {
        return $this->belongsTo(Account::class, 'debit_id');
    }

    public function credit(): BelongsTo
    {
        return $this->belongsTo(Account::class, 'credit_id');
    }

    public function claim(): BelongsTo
    {
        return $this->belongsTo(Transaction::class, 'claim_id');
    }

    public function repayments(): HasMany
    {
        return $this->hasMany(Transaction::class, 'claim_id', 'id');
    }

    public function person(): BelongsTo
    {
        return $this->belongsTo(Person::class);
    }

    public function bankTransactions(): HasMany
    {
        return $this->hasMany(BankTransaction::class);
    }
}
