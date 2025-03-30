<?php

declare(strict_types=1);

namespace App\Models;

use App\Types\AccountType;
use App\Types\Money;
use App\Types\TransactionCollection;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @property int $id
 * @property string $name
 * @property bool $archived
 * @property bool $recurring
 * @property float|null $interest_rate
 * @property AccountType $type
 * @property-read BankAccount $bankAccount
 */
final class Account extends Model
{
    protected function casts(): array
    {
        return [
            'archived' => 'boolean',
            'recurring' => 'boolean',
        ];
    }

    protected function type(): Attribute
    {
        return Attribute::make(
            get: fn () => AccountType::make($this->group_id),
            set: fn (AccountType $type) => [
                'group_id' => $type->value,
            ],
        );
    }

    public static function splitAccount(): Account
    {
        return Account::firstOrCreate([
            'name' => '_split_transactions',
        ], [
            'archived' => false,
            'recurring' => false,
            'type' => AccountType::ROOT,
        ]);
    }

    public function transactions(): TransactionCollection
    {
        return TransactionCollection::allWithAccount($this->id);
    }

    public function debitTransactions(): TransactionCollection
    {
        return TransactionCollection::from(
            $this->hasMany(Transaction::class, 'debit_id')->get()
        );
    }

    public function creditTransactions(): TransactionCollection
    {
        return TransactionCollection::from(
            $this->hasMany(Transaction::class, 'credit_id')->get()
        );
    }

    public function balance(): Money
    {
        return once(function () {
            $debit = $this->debitTransactions()->sumValues();
            $credit = $this->creditTransactions()->sumValues();

            return $debit->minus($credit);
        });
    }

    protected function bankAccount(): HasOne
    {
        return $this->hasOne(BankAccount::class, 'account_id');
    }
}
