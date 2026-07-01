<?php

namespace App\Mcp\Tools;

use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\ResponseFactory;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Tool;
use App\Models\BankTransaction;
use App\Models\BankProposal;

#[Description('View the next few unresolved bank transactions.')]
class UnresolvedBankTransactionsTool extends Tool
{
    /**
     * Handle the tool request.
     */
    public function handle(Request $request): ResponseFactory
    {
        $bankAccountId = $request->integer('bank_account_id');
        $limit = $request->integer('limit');

        $bankTransactions = BankTransaction::query()
            ->where('bank_account_id', $bankAccountId)
            ->whereNull('transaction_id')
            ->where('skipped', false)
            ->limit($limit)
            ->get()
            ->filter(fn ($record) => BankProposal::findFor($record) === null)
            ->map(fn ($record) => [
                'id' => $record->id,
                'text' => $record->text,
                'date' => $record->date,
                'money' => (string)$record->money,
            ]);

        return Response::structured([
            'bankTransactions' => $bankTransactions,
        ]);
    }

    /**
     * Get the tool's input schema.
     *
     * @return array<string, \Illuminate\JsonSchema\Types\Type>
     */
    public function schema(JsonSchema $schema): array
    {
        return [
            'limit' => $schema->integer()
                ->description('The number of unresolved transactions to get')
                ->required(),

            'bank_account_id' => $schema->integer()
                ->description('The bankaccount id to get the unresolved transactions from')
                ->required(),
        ];
    }

    /**
     * Get the tool's output schema.
     *
     * @return array<string, \Illuminate\JsonSchema\Types\Type>
     */
    public function outputSchema(JsonSchema $schema): array
    {
        return [
            'bankTransactions' => $schema->array()
                ->items(
                    $schema->object([
                        'id' => $schema->integer()->description('id')->required(),
                        'text' => $schema->string()->description('text description from bank export')->required(),
                        'date' => $schema->string()->description('transaction date')->required(),
                        'money' => $schema->string()->description('value and currency')->required(),
                    ]),
                )
                ->required(),
        ];
    }
}
