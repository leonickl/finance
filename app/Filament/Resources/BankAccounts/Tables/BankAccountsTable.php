<?php

declare(strict_types=1);

namespace App\Filament\Resources\BankAccounts\Tables;

use App\Bank\UploadHandler;
use App\Models\BankAccount;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Storage;

final class BankAccountsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('bank')
                    ->searchable(),
                TextColumn::make('account.name')
                    ->searchable(),
                TextColumn::make('balance')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('currency')
                    ->searchable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
                Action::make('upload')
                    ->label(__('upload'))
                    ->icon('heroicon-o-arrow-up-circle')
                    ->form([
                        FileUpload::make('file')
                            ->acceptedFileTypes(['text/plain', 'text/csv'])
                            ->disk('local')
                            ->visibility('private'),
                        Textarea::make('text'),
                    ])
                    ->action(function (array $data, BankAccount $bankAccount): void {
                        $contents = $data['text'];

                        $path = $data['file'];

                        if ($path) {
                            $contents = Storage::disk('local')->get($path);
                        }

                        if ( ! $contents) {
                            Notification::make()
                                ->title('No contents given')
                                ->danger()
                                ->send();

                            return;
                        }

                        $result = new UploadHandler($bankAccount)->uploadText($contents);

                        Notification::make()
                            ->title("Uploaded {$result->count} bank transactions")
                            ->success()
                            ->send();
                    }),
                Action::make('compare')
                    ->label(__('compare'))
                    ->url(fn (BankAccount $bankAccount) => route('filament.finance.resources.bank-accounts.compare', $bankAccount)),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
