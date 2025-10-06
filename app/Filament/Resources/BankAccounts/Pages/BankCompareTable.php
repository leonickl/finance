<?php

declare(strict_types=1);

namespace App\Filament\Resources\BankAccounts\Pages;

use App\Filament\Resources\BankAccounts\BankAccountResource;
use App\Models\BankTransaction;
use Filament\Actions\Action;
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
        return $table
            ->query(BankTransaction::query()) // TODO only of current bank account
            ->columns([
                TextColumn::make('id'),
                TextColumn::make('text')
                    ->getStateUsing(fn($record) => strlen($record->text) > 50 ? substr($record->text, 0, 30).'...' : $record->text),
                TextColumn::make('date'),
            ])
            ->actions([
                Action::make('apply-proposal'),
                Action::make('make-proposal'),
            ]);
    }
}
