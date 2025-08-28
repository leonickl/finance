<?php

declare(strict_types=1);

namespace App\Bank;

use App\Bank\Bank as BankEnum;
use App\Models\BankAccount;
use App\Models\BankTransaction;

final readonly class UploadHandler
{
    public function __construct(private BankAccount $bankAccount) {}

    public function uploadText(string $content): void
    {
        // trim spaces and quotes > only json should be left
        $content = trim($content, " \n\r\t\v\0\"\'´`");

        $result = $this->parser()->parse($content);

        $this->bankAccount->balance = $result->balance();

        $result->transactions()->each(fn (BankTransaction $transaction) => $transaction->save());
    }

    private function parser(): Parser
    {
        return BankEnum::from($this->bankAccount->bank)->makeParser($this->bankAccount->id);
    }
}
