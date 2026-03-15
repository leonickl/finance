<?php

declare(strict_types=1);

namespace App\Filament\Resources\BankProposals\Pages;

use App\Filament\Resources\BankProposals\BankProposalResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

final class ListBankProposals extends ListRecords
{
    protected static string $resource = BankProposalResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
