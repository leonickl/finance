<?php

namespace App\Filament\Resources\Transactions\Schemas;

use App\Models\Account;
use App\Models\Transaction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class TransactionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('debit_id')
                    ->relationship('debit')
                    ->getOptionLabelFromRecordUsing(fn (Account $account) => $account->fullname)
                    ->required(),
                Select::make('credit_id')
                    ->relationship('credit')
                    ->getOptionLabelFromRecordUsing(fn (Account $account) => $account->fullname)
                    ->required()
                    ->reactive(),
                TextInput::make('value')
                    ->required()
                    ->numeric(),
                TextInput::make('currency')
                    ->default('EUR')
                    ->required(),
                DatePicker::make('timestamp')
                    ->required()
                    ->default(now()),
                TextInput::make('text')
                    ->required(),
                Select::make('claim_id')
                    ->relationship('claim')
                    ->options(function (callable $get) {
                        $selectedCredit = $get('credit_id');

                        if (! $selectedCredit) {
                            return [];
                        }

                        return Transaction::where('debit_id', $selectedCredit)
                            ->get()
                            ->filter(fn ($claim) => $claim->rest?->isPositive() ?? false)
                            ->mapWithKeys(fn ($claim) => [
                                $claim->id => "({$claim->id}) {$claim->date} {$claim->text} {$claim->rest} / {$claim->value}",
                            ])
                            ->toArray();
                    })
                    ->visible(fn (callable $get) => Account::find($get('credit_id'))?->type->isClaimType() ?? false)
                    ->columnSpanFull(),
            ]);
    }
}
