<?php

declare(strict_types=1);

namespace App\Models;

use App\Bank\BankTransactionDto;
use App\Models\Helpers\CurrencyAttribute;
use App\Types\Currency;
use App\Types\Date\Date;
use App\Types\Money;
use App\Types\TransactionCollection;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $bank_account_id
 * @property string $date
 * @property string $text
 * @property float $value
 * @property Currency $currency
 * @property Money $money
 * @property int|null $transaction_id
 * @property bool $skipped
 * @property Carbon $created_at
 * @property string $src
 * @property string|null $iban
 * @property-read Transaction $transaction
 * @property-read BankAccount $bankAccount
 */
final class BankTransaction extends Model
{
    use CurrencyAttribute;

    protected $fillable = [
        'transaction_id',
    ];

    protected function casts(): array
    {
        return [
            'value' => 'float',
            'skipped' => 'boolean',
            'created_at' => 'datetime',
        ];
    }

    public function possibleTransactions(): TransactionCollection
    {
        return Transaction::findForBankTransaction($this);
    }

    public static function countOccurrences(BankTransactionDto $transaction): int
    {
        return self::query()
            ->where('bank_account_id', $transaction->bankAccountId)
            ->where('date', $transaction->date->dashedDate())
            ->where('text', $transaction->text)
            ->where('value', $transaction->value->float())
            ->count();
    }

    public function money(): Attribute
    {
        return Attribute::make(
            get: fn () => Money::new($this->value, $this->currency),
            set: fn (Money $value) => [
                'value' => $value->float(),
                'currency' => $value->currency(),
            ]
        );
    }

    public function date(): Date
    {
        return Date::of(Carbon::make($this->date));
    }

    public function dto(): BankTransactionDto
    {
        return new BankTransactionDto(
            date: $this->date(),
            text: $this->text,
            value: Money::new($this->value, $this->currency),
            iban: $this->iban,
            bankAccountId: $this->bank_account_id,
        );
    }

    public function bankAccount(): BelongsTo
    {
        return $this->belongsTo(BankAccount::class, 'bank_account_id');
    }

    public function transaction(): BelongsTo
    {
        return $this->belongsTo(Transaction::class, 'transaction_id');
    }
}
