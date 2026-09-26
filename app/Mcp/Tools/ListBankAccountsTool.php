<?php

declare(strict_types=1);

namespace App\Mcp\Tools;

use App\Models\BankAccount;
use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\ResponseFactory;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Tool;

#[Description('Get a list of all bank accounts including details and the linked account.')]
final class ListBankAccountsTool extends Tool
{
    public function handle(Request $request): ResponseFactory
    {
        $bankAccounts = BankAccount::with('account')
            ->get()
            ->map(fn (BankAccount $bankAccount) => [
                'id' => $bankAccount->id,
                'bank' => $bankAccount->bank,
                'currency' => $bankAccount->currency->code(),
                'balance' => $bankAccount->balance,
                'account' => [
                    'id' => $bankAccount->account_id,
                    'name' => $bankAccount->account->name,
                    'fullname' => $bankAccount->account->fullname,
                ],
            ])
            ->values()
            ->toArray();

        return Response::structured(['bank_accounts' => $bankAccounts]);
    }
}
