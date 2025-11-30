<?php

declare(strict_types=1);

namespace App\Filament\Resources\BankAccounts\Pages;

use App\Filament\Resources\BankAccounts\BankAccountResource;
use App\Models\BankProposal;
use App\Models\BankTransaction;
use App\Types\Date\Date;
use Exception;
use Filament\Actions\Action;
use Filament\Resources\Pages\Concerns\InteractsWithRecord;
use Filament\Resources\Pages\Page;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use App\Models\Account;
use App\Models\Transaction;
use Illuminate\Support\Carbon;

final class BankCompareTable extends Page implements HasTable
{
    use InteractsWithRecord;
    use InteractsWithTable;

    protected static string $resource = BankAccountResource::class;

    protected string $view = 'filament.resources.bank-accounts.pages.bank-compare-table';

    public function mount(int | string $record): void
    {
        $this->record = $this->resolveRecord($record);
    }

    public function table(Table $table): Table
    {
        $query = BankTransaction::query()
            ->where('bank_account_id', $this->record->id)
            ->whereNull('transaction_id')
            ->where('skipped', false);

        return $table
            ->query($query)
            ->columns([
                TextColumn::make('id'),
                TextColumn::make('text')
                    ->getStateUsing(fn ($record) => mb_strlen($record->text) > 30
                        ? mb_substr($record->text, 0, 30) . '...' : $record->text),
                TextColumn::make('date'),
                TextColumn::make('money')
                    ->alignEnd(),
                TextColumn::make('text')
                    ->getStateUsing(fn ($record) => mb_strlen($record->text) > 30
                        ? mb_substr($record->text, 0, 30) . '...' : $record->text),
                TextColumn::make('proposal')
                    ->getStateUsing(fn ($record) => BankProposal::findFor($record)?->label()),
            ])
            ->actions([
                Action::make('apply')
                    ->action(function (BankTransaction $record): void {
                        $proposal = BankProposal::findFor($record);

                        if ( ! $proposal) {
                            throw new Exception('cannot apply null-proposal');
                        }

                        if ($record->value > 0) {
                            $debit = $record->bankAccount->account;
                            $credit = Account::query()->find($proposal->account) ?? Account::unknown();
                        } else {
                            $debit = Account::query()->find($proposal->account) ?? Account::unknown();
                            $credit = $record->bankAccount->account;
                        }

                        $transaction = Transaction::create(
                            debit: $debit,
                            credit: $credit,
                            value: $record->money->abs(),
                            text: $proposal->text_proposal,
                            date: $record->date(),
                        );

                        $record->transaction_id = $transaction->id;
                        $record->save();
                    })
                    ->isEnabled(),
                Action::make('make-proposal'),
            ])
            ->striped();
    }
}
