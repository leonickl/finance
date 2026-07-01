<?php

namespace App\Mcp\Tools;

use App\Models\BankAccount;
use App\Models\BankProposal;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\ResponseFactory;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Tool;

#[Description('Apply all available proposals for a bank account')]
class ApplyAllProposalsTool extends Tool
{
    /**
     * Handle the tool request.
     */
    public function handle(Request $request): ResponseFactory
    {
        $bankAccountId = $request->integer('bank_account_id');

        $bankAccount = BankAccount::query()->findOrFail($bankAccountId);

        $counter = 0;

        foreach ($bankAccount->bankTransactions as $bankTransaction) {
            $counter += BankProposal::applyFor($bankTransaction) ? 1 : 0;
        }

        return Response::make(Response::text("Applied {$counter} proposals"));
    }

    /**
     * Get the tool's input schema.
     *
     * @return array<string, JsonSchema>
     */
    public function schema(JsonSchema $schema): array
    {
        return [
            'bank_account_id' => $schema->integer()
                ->description('The bankaccount id to get the proposals from')
                ->required(),
        ];
    }
}
