<?php

declare(strict_types=1);

namespace App\Bank;

use App\Bank\Bank as BankEnum;
use App\Models\BankAccount;
use App\Models\BankTransaction;
use Illuminate\Support\Facades\File;

final readonly class UploadHandler
{
    public function __construct(private BankAccount $bankAccount) {}

    public function uploadFile(string $csvPath): void
    {
        $this->uploadText(File::get($csvPath));
    }

    public function uploadText(string $content): void
    {
        // trim spaces and quotes > only json should be left
        $content = trim($content, " \n\r\t\v\0\"\'´`");

        $result = $this->parser()->parse($content);

        $this->bankAccount->updateBalance($result->balance());

        $result->transactions()->each(fn (BankTransaction $transaction) => $transaction->save());
    }

    public function parser(): Parser
    {
        return BankEnum::from($this->bankAccount->bank)->makeParser($this->bankAccount->id);
    }
}
