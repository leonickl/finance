<?php

declare(strict_types=1);

namespace App\Filament\Resources\Transactions\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

final class TransactionInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('debit.name')
                    ->label(__('debit'))
                    ->getStateUsing(fn ($record) => $record->debit->fullname),
                TextEntry::make('credit.name')
                    ->label(__('credit'))
                    ->getStateUsing(fn ($record) => $record->credit->fullname),
                TextEntry::make('value')
                    ->getStateUsing(fn ($record) => (string) $record->value()),
                TextEntry::make('timestamp')
                    ->date(),
                TextEntry::make('text')
                    ->label(__('text'))
                    ->columnSpanFull(),
                TextEntry::make('claim.id')
                    ->label(__('claim_id'))
                    ->getStateUsing(fn ($record) => $record->claim?->id ?? '---'),
                TextEntry::make('group_uid')
                    ->getStateUsing(fn ($record) => $record->group_uid ?? '---'),
                TextEntry::make('person.name')
                    ->getStateUsing(fn ($record) => $record->person?->name ?? '---'),
                TextEntry::make('currency'),
                TextEntry::make('created_at')
                    ->dateTime(),
                TextEntry::make('updated_at')
                    ->dateTime(),
            ]);
    }
}
