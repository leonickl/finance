<?php

declare(strict_types=1);

namespace App\Filament\Resources\Accounts\Pages;

use App\Filament\Resources\Accounts\AccountResource;
use App\Models\Account;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

final class ViewAccount extends ViewRecord
{
    protected static string $resource = AccountResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
            Action::make('toggle-archived')
                ->action(function (Account $account): void {
                    $account->archived = ! $account->archived;
                    $account->save();
                }),
        ];
    }
}
