<?php

declare(strict_types=1);

namespace App\Filament\Resources\Accounts\Pages;

use App\Filament\Resources\Accounts\AccountResource;
use App\Models\Transaction;
use Filament\Actions\Action;
use Filament\Resources\Pages\Concerns\InteractsWithRecord;
use Filament\Resources\Pages\Page;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;

final class AccountTransactionsTable extends Page implements HasTable
{
    use InteractsWithRecord;
    use InteractsWithTable;

    protected static string $resource = AccountResource::class;

    protected string $view = 'filament.resources.accounts.pages.account-transactions-table';

    public function mount(int | string $record): void
    {
        $this->record = $this->resolveRecord($record);
    }

    public function table(Table $table): Table
    {
        $query = Transaction::query()
            ->where('debit_id', $this->record->id)
            ->orWhere('credit_id', $this->record->id);

        return $table
            ->query($query)
            ->columns([
                TextColumn::make('id'),
                TextColumn::make('other_account')
                    ->getStateUsing(fn ($record) => $this->record->id === $record->debit_id
                        ? $record->credit->fullname
                        : $record->debit->fullname),
                TextColumn::make('value')
                    ->getStateUsing(fn ($record) => $this->record->id === $record->debit_id
                        ? $record->value()
                        : $record->value()->negate())
                    ->numeric()
                    ->sortable(),
                TextColumn::make('timestamp')
                    ->date()
                    ->sortable(),
                TextColumn::make('text')
                    ->getStateUsing(fn ($record) => mb_strlen($record->text) > 40
                        ? mb_substr($record->text, 0, 37) . '…'
                        : $record->text),
            ])
            ->defaultSort('timestamp', direction: 'desc')
            ->headerActions([
                Action::make('back')
                    ->url(fn () => route('filament.finance.resources.accounts.index', $this->record)),
            ])
            ->recordClasses(fn (Transaction $record) => $this->record->id === $record->debit_id
                ? 'dark:bg-green-700 bg-green-300'
                : 'dark:bg-red-500 bg-red-400');
    }
}
