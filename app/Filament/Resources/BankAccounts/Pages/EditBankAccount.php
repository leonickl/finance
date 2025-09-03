<?php

declare(strict_types=1);

namespace App\Filament\Resources\BankAccounts\Pages;

use App\Filament\Resources\BankAccounts\BankAccountResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

final class EditBankAccount extends EditRecord
{
    protected static string $resource = BankAccountResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
