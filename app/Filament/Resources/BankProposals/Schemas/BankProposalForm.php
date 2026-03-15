<?php

namespace App\Filament\Resources\BankProposals\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class BankProposalForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Toggle::make('value_is_positive')
                    ->required(),
                TextInput::make('text_contains')
                    ->required(),
                TextInput::make('account_proposal')
                    ->required()
                    ->numeric(),
                TextInput::make('text_proposal')
                    ->required(),
            ]);
    }
}
