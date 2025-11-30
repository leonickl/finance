<?php

declare(strict_types=1);

namespace App\Filament\Resources\BankAccounts\Pages;

use App\Filament\Resources\BankAccounts\BankAccountResource;
use App\Models\BankProposal;
use App\Models\BankTransaction;
use App\Models\Transaction;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\Concerns\InteractsWithRecord;
use Filament\Resources\Pages\Page;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;

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

                        if ($record->value > 0) {
                            $debit = $record->bankAccount->account;
                            $credit = $proposal->accountProposal;
                        } else {
                            $debit = $proposal->accountProposal;
                            $credit = $record->bankAccount->account;
                        }

                        if ($debit === null || $credit === null) {
                            Notification::make()
                                ->title("Account {$proposal->account} not found")
                                ->warning()
                                ->send();

                            return;
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

                        Notification::make()
                            ->title("Created Transaction {$transaction->id}")
                            ->success()
                            ->send();
                    })
                    ->visible(fn ($record) => BankProposal::findFor($record) !== null),
                Action::make('make-proposal')
                    ->visible(fn ($record) => BankProposal::findFor($record) === null),
            ])
            ->striped();
    }
}
