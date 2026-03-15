<?php

namespace App\Filament\Resources\BankProposals;

use App\Filament\Resources\BankProposals\Pages\CreateBankProposal;
use App\Filament\Resources\BankProposals\Pages\EditBankProposal;
use App\Filament\Resources\BankProposals\Pages\ListBankProposals;
use App\Filament\Resources\BankProposals\Schemas\BankProposalForm;
use App\Filament\Resources\BankProposals\Tables\BankProposalsTable;
use App\Models\BankProposal;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class BankProposalResource extends Resource
{
    protected static ?string $model = BankProposal::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'text_contains';

    public static function form(Schema $schema): Schema
    {
        return BankProposalForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return BankProposalsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListBankProposals::route('/'),
            'create' => CreateBankProposal::route('/create'),
            'edit' => EditBankProposal::route('/{record}/edit'),
        ];
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
