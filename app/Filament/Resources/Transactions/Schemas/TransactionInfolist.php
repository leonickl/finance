<?php

namespace App\Filament\Resources\Transactions\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class TransactionInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('debit.name'),
                TextEntry::make('credit.name'),
                TextEntry::make('value')
                    ->numeric(),
                TextEntry::make('timestamp')
                    ->date(),
                TextEntry::make('claim.id'),
                TextEntry::make('group_uid'),
                TextEntry::make('person.name'),
                TextEntry::make('currency'),
                TextEntry::make('created_at')
                    ->dateTime(),
                TextEntry::make('updated_at')
                    ->dateTime(),
                TextEntry::make('deleted_at')
                    ->dateTime(),
            ]);
    }
}
