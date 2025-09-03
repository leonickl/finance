<?php

declare(strict_types=1);

namespace App\Filament\Resources\BankTransactions\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

final class BankTransactionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('bank_account_id')
                    ->relationship('bankAccount', 'id')
                    ->required(),
                DatePicker::make('date')
                    ->required(),
                Textarea::make('text')
                    ->required()
                    ->columnSpanFull(),
                TextInput::make('value')
                    ->required()
                    ->numeric(),
                TextInput::make('currency')
                    ->required(),
                Select::make('transaction_id')
                    ->relationship('transaction', 'id'),
                Toggle::make('skipped')
                    ->required(),
                TextInput::make('src')
                    ->required(),
                TextInput::make('iban'),
            ]);
    }
}
