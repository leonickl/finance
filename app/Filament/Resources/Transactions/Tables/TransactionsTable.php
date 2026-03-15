<?php

declare(strict_types=1);

namespace App\Filament\Resources\Transactions\Tables;

use App\Models\Account;
use Filament\Actions\BulkAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Support\Collection;

final class TransactionsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->numeric(),
                TextColumn::make('debit.name')
                    ->searchable(),
                TextColumn::make('credit.name')
                    ->searchable(),
                TextColumn::make('value')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('timestamp')
                    ->date()
                    ->sortable(),
                TextColumn::make('claim.id')
                    ->searchable(),
                TextColumn::make('text')
                    ->getStateUsing(fn ($record) => mb_strlen($record->text) > 40
                        ? mb_substr($record->text, 0, 37) . '…'
                        : $record->text),
                TextColumn::make('group_uid')
                    ->searchable(),
                TextColumn::make('person.name')
                    ->searchable(),
                TextColumn::make('currency')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TrashedFilter::make(),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                DeleteBulkAction::make(),
                RestoreBulkAction::make(),
                BulkAction::make('set-debit')
                    ->schema([
                        Select::make('debit-account')
                            ->options(Account::allNotArchived()
                                ->sortBy(fn ($record) => $record->fullname)
                                ->pluck('fullname', 'id'))
                            ->required(),
                    ])
                    ->action(function (array $data, Collection $transactions): void {
                        $transactions->each->update([
                            'debit_id' => $data['debit-account'],
                        ]);
                    }),
                BulkAction::make('set-credit')
                    ->schema([
                        Select::make('credit-account')
                            ->options(Account::allNotArchived()
                                ->sortBy(fn ($record) => $record->fullname)
                                ->pluck('fullname', 'id'))
                            ->required(),
                    ])
                    ->action(function (array $data, Collection $transactions): void {
                        $transactions->each->update([
                            'credit_id' => $data['credit-account'],
                        ]);
                    }),
            ]);
    }
}
