<?php

declare(strict_types=1);

namespace App\Filament\Resources\Accounts\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

final class AccountInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('name'),
                IconEntry::make('archived')
                    ->boolean(),
                TextEntry::make('group_id')
                    ->numeric(),
                IconEntry::make('recurring')
                    ->boolean(),
                TextEntry::make('created_at')
                    ->dateTime(),
                TextEntry::make('updated_at')
                    ->dateTime(),
                TextEntry::make('deleted_at')
                    ->dateTime(),
                TextEntry::make('interest_rate')
                    ->numeric(),
            ]);
    }
}
