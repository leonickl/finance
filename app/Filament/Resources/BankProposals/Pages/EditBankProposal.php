<?php

namespace App\Filament\Resources\BankProposals\Pages;

use App\Filament\Resources\BankProposals\BankProposalResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Resources\Pages\EditRecord;

class EditBankProposal extends EditRecord
{
    protected static string $resource = BankProposalResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }
}
