<?php

declare(strict_types=1);

namespace App\Filament\Resources\BankTransactions\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

final class BankTransactionInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('bankAccount.id'),
                TextEntry::make('date')
                    ->date(),
                TextEntry::make('value')
                    ->numeric(),
                TextEntry::make('currency'),
                TextEntry::make('transaction.id'),
                IconEntry::make('skipped')
                    ->boolean(),
                TextEntry::make('src'),
                TextEntry::make('iban'),
                TextEntry::make('created_at')
                    ->dateTime(),
                TextEntry::make('updated_at')
                    ->dateTime(),
                TextEntry::make('deleted_at')
                    ->dateTime(),
            ]);
    }
}
