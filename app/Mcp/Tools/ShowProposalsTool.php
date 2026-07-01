<?php

namespace App\Mcp\Tools;

use App\Models\BankProposal;
use App\Models\BankTransaction;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\ResponseFactory;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Tool;

#[Description('Shows available proposals for a bank account')]
class ShowProposalsTool extends Tool
{
    /**
     * Handle the tool request.
     */
    public function handle(Request $request): ResponseFactory
    {
        $bankAccountId = $request->integer('bank_account_id');

        $bankTransactions = BankTransaction::query()
            ->where('bank_account_id', $bankAccountId)
            ->whereNull('transaction_id')
            ->where('skipped', false)
            ->get()
            ->map(fn ($record) => [
                'id' => $record->id,
                'text' => $record->text,
                'date' => $record->date,
                'money' => (string)$record->money,
                'proposal' => BankProposal::findFor($record)?->toArray(),
            ])
            ->filter(fn ($record) => $record['proposal'] !== null)
            ->values()
            ->toArray();

        return Response::structured([
            'bankTransactions' => $bankTransactions,
        ]);
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
                        'proposal' => $schema->object([
                            'id' => $schema->integer()->required(),
                            'value_is_positive' => $schema->boolean()->required()->description('whether positive or negative values are matched'),
                            'text_contains' => $schema->string()->description('what the bank transaction\'s text must contain for a match'),
                            'text_proposal' => $schema->string()->description('what text is proposed for the new transaction'),
                            'accountProposal' => $schema->object([
                                'id' => $schema->integer()->required(),
                                'fullname' => $schema->string()->required(),
                            ])->required()->description('what account is proposed for the new transaction'),
                        ])->description('proposed transaction to create'),
                    ]),
                )
                ->required(),
        ];
    }
}
