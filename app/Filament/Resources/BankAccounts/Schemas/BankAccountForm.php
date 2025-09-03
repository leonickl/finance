<?php

declare(strict_types=1);

namespace App\Filament\Resources\BankAccounts\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

final class BankAccountForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('bank')
                    ->required(),
                Select::make('account_id')
                    ->relationship('account', 'name')
                    ->required(),
                TextInput::make('balance')
                    ->numeric(),
                TextInput::make('currency')
                    ->required(),
            ]);
    }
}
