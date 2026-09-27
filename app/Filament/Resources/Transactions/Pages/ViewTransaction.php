<?php

declare(strict_types=1);

namespace App\Filament\Resources\Transactions\Pages;

use App\Filament\Resources\Transactions\TransactionResource;
use Filament\Actions\EditAction;
use Filament\Actions\Action;
use Filament\Resources\Pages\ViewRecord;
use App\Models\Transaction;
use Filament\Notifications\Notification;

final class ViewTransaction extends ViewRecord
{
    protected static string $resource = TransactionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
            Action::make('swap')
                ->action(function (Transaction $transaction) {
                    $debit_id = $transaction->debit_id;
                    $credit_id = $transaction->credit_id;

                    $transaction->debit_id = $credit_id;
                    $transaction->credit_id = $debit_id;

                    $transaction->save();

                    Notification::make()
                        ->title('Swapped debit and credit account')
                        ->success()
                        ->send();
                })
        ];
    }
}
