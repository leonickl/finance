<?php

declare(strict_types=1);

namespace App\Models;

use App\Types\AccountType;
use App\Types\Money;
use App\Types\TransactionCollection;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Collection;

/**
 * @property int $id
 * @property string $name
 * @property bool $archived
 * @property bool $recurring
 * @property float|null $interest_rate
 * @property AccountType $type
 * @property-read string $fullname
 * @property-read BankAccount $bankAccount
 */
final class Account extends Model
{
    protected $fillable = [
        'name',
        'archived',
        'type',
        'recurring',
        'interest_rate',
    ];

    protected function casts(): array
    {
        return [
            'interest_rate' => 'float',
            'archived' => 'boolean',
            'recurring' => 'boolean',
            'type' => AccountType::class,
        ];
    }

    protected function fullname(): Attribute
    {
        return Attribute::get(fn () => __($this->type->name) . ' – ' . $this->name);
    }

    public static function allNotArchived(): Collection
    {
        return self::query()
            ->where('archived', 0)
            ->get();
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

    public function transactions(bool $desc = false): TransactionCollection
    {
        return TransactionCollection::allWithAccount($this->id, $desc);
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

    public function bankAccount(): HasOne
    {
        return $this->hasOne(BankAccount::class, 'account_id');
    }

    public static function unknown(): Account
    {
        return Account::firstOrCreate([
            'name' => '_unknown',
        ], [
            'archived' => false,
            'recurring' => false,
            'type' => AccountType::ROOT,
        ]);
    }
}
