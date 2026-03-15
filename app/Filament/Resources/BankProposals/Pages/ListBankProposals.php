<?php

namespace App\Filament\Resources\BankProposals\Pages;

use App\Filament\Resources\BankProposals\BankProposalResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListBankProposals extends ListRecords
{
    protected static string $resource = BankProposalResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
