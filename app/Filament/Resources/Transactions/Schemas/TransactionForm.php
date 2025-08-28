<?php

namespace App\Filament\Resources\Transactions\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class TransactionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('debit_id')
                    ->relationship('debit', 'name')
                    ->required(),
                Select::make('credit_id')
                    ->relationship('credit', 'name')
                    ->required(),
                TextInput::make('value')
                    ->required()
                    ->numeric(),
                Textarea::make('text')
                    ->required()
                    ->columnSpanFull(),
                DatePicker::make('timestamp')
                    ->required(),
                Select::make('claim_id')
                    ->relationship('claim', 'id'),
                TextInput::make('group_uid'),
                Select::make('person_id')
                    ->relationship('person', 'name'),
                TextInput::make('currency')
                    ->required(),
            ]);
    }
}
