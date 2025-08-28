<?php

namespace App\Filament\Resources\Accounts\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class AccountForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                Toggle::make('archived')
                    ->required(),
                TextInput::make('group_id')
                    ->numeric(),
                Toggle::make('recurring')
                    ->required(),
                TextInput::make('interest_rate')
                    ->numeric(),
            ]);
    }
}
