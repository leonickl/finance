<?php

namespace App\Mcp\Tools;

use App\Models\BankTransaction;
use App\Models\Transaction;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\ResponseFactory;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Tool;

#[Description('Link an existing transaction to an unresolved bank transaction. This does NOT create a new transaction, it only sets the link.')]
class LinkBankTransactionToTransactionTool extends Tool
{
    public function handle(Request $request): ResponseFactory
    {
        $bankTransactionId = $request->integer('bank_transaction_id');
        $transactionId = $request->integer('transaction_id');

        $bankTransaction = BankTransaction::findOrFail($bankTransactionId);

        if ($bankTransaction->transaction_id !== null) {
            return Response::structured([
                'success' => false,
                'message' => "Bank transaction {$bankTransaction->id} is already linked to transaction {$bankTransaction->transaction_id}.",
            ]);
        }

        $transaction = Transaction::findOrFail($transactionId);

        $bankTransaction->transaction_id = $transaction->id;
        $bankTransaction->save();

        return Response::structured([
            'success' => true,
            'message' => "Linked bank transaction {$bankTransaction->id} to transaction {$transaction->id}: {$transaction->debit->fullname} to {$transaction->credit->fullname} {$transaction->value()}.",
            'bank_transaction_id' => $bankTransaction->id,
            'transaction_id' => $transaction->id,
        ]);
    }

    public function schema(JsonSchema $schema): array
    {
        return [
            'bank_transaction_id' => $schema->integer()
                ->description('The ID of the unresolved bank transaction to link.')
                ->required(),

            'transaction_id' => $schema->integer()
                ->description('The ID of the existing transaction to link to.')
                ->required(),
        ];
    }

    public function outputSchema(JsonSchema $schema): array
    {
        return [
            'success' => $schema->boolean()->description('Whether the link was created')->required(),
            'message' => $schema->string()->description('Result message')->required(),
            'bank_transaction_id' => $schema->integer()->description('The bank transaction ID'),
            'transaction_id' => $schema->integer()->description('The transaction ID'),
        ];
    }
}
