<?php

declare(strict_types=1);

namespace App\Bank;

use App\Bank\Bank as BankEnum;
use App\Models\BankAccount;
use App\Models\BankTransaction;

final readonly class UploadHandler
{
    public function __construct(private BankAccount $bankAccount) {}

    public function uploadText(string $content): object
    {
        // trim spaces and quotes > only json should be left
        $content = mb_trim($content, " \n\r\t\v\0\"\'´`");

        $result = $this->parser()->parse($content);

        $this->bankAccount->balance = $result->balance();

        $transactions = $result->transactions();

        $transactions->each(fn (BankTransaction $transaction) => $transaction->save());

        return (object) ['count' => $transactions->count()];
    }

    private function parser(): Parser
    {
        return BankEnum::from($this->bankAccount->bank)->makeParser($this->bankAccount->id);
    }
}
