<?php

declare(strict_types=1);

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property bool $value_is_positive
 * @property string $text_contains
 * @property int $account_proposal
 * @property string $text_proposal
 * @property-read  Account $accountProposal
 */
final class BankProposal extends Model
{
    protected $fillable = [
        'value_is_positive',
        'text_contains',
        'account_proposal',
        'text_proposal',
    ];

    protected function casts(): array
    {
        return ['value_is_positive' => 'boolean'];
    }

    public static function findFor(BankTransaction $transaction): ?self
    {
        return self::all()
            ->first(fn (self $proposal) => str_contains(
                mb_strtolower(str_replace(["\r\n", "\n"], [' ', ' '], $transaction->text)),
                mb_strtolower(str_replace(["\r\n", "\n"], [' ', ' '], $proposal->text_contains)),
            ));
    }

    public static function applyFor(BankTransaction $record)
    {
        $proposal = BankProposal::findFor($record);

        if ($record->value > 0) {
            $debit = $record->bankAccount->account;
            $credit = $proposal->accountProposal;
        } else {
            $debit = $proposal->accountProposal;
            $credit = $record->bankAccount->account;
        }

        if ($debit === null || $credit === null) {
            throw new Exception('Proposal account not found');
        }

        $transaction = Transaction::create(
            debit: $debit,
            credit: $credit,
            value: $record->money->abs(),
            text: $proposal->text_proposal ?: $record->text,
            date: $record->date(),
        );

        $record->transaction_id = $transaction->id;
        $record->save();

        return $transaction;
    }

    public function label(): string
    {
        return $this->text_proposal . ' ' . $this->accountProposal->fullname;
    }

    public function accountProposal(): BelongsTo
    {
        return $this->belongsTo(Account::class, 'account_proposal');
    }
}
