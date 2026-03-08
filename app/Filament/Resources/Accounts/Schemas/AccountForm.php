<?php

declare(strict_types=1);

namespace App\Filament\Resources\Accounts\Schemas;

use App\Types\AccountType;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

final class AccountForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                Toggle::make('archived')
                    ->required(),
                Radio::make('type')
                    ->label('Type')
                    ->options(AccountType::class),
                Toggle::make('recurring')
                    ->required(),
                TextInput::make('interest_rate')
                    ->numeric(),
            ]);
    }
}
