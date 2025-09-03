<?php

namespace App\Filament\Resources\Accounts\Schemas;

use App\Types\AccountType;
use Filament\Forms\Components\Select;
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
                Select::make('group_id')
                    ->label('Type')
                    ->options(collect(AccountType::cases())->mapWithKeys(fn ($case) => [$case->value => $case->name])),
                Toggle::make('recurring')
                    ->required(),
                TextInput::make('interest_rate')
                    ->numeric(),
            ]);
    }
}
