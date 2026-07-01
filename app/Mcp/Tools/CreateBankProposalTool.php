<?php

namespace App\Mcp\Tools;

use App\Models\BankProposal;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Tool;

#[Description('Create a proposal for a specific bank transaction that can be re-used afterwards.')]
class CreateBankProposalTool extends Tool
{
    /**
     * Handle the tool request.
     */
    public function handle(Request $request): Response
    {
        $validated = $request->validate([
            'value_is_positive' => ['required', 'boolean'],
            'text_contains' => ['required', 'string'],
            'account_proposal' => ['required', 'integer'],
            'text_proposal' => ['required', 'string'],
        ]);

        $proposal = new BankProposal;
        $proposal->value_is_positive = $validated['value_is_positive'];
        $proposal->text_contains = $validated['text_contains'];
        $proposal->account_proposal = $validated['account_proposal'];
        $proposal->text_proposal = $validated['text_proposal'];
        $proposal->save();

        return Response::text("Created bank proposal with id {$proposal->id}");
    }

    /**
     * Get the tool's input schema.
     *
     * @return array<string, JsonSchema>
     */
    public function schema(JsonSchema $schema): array
    {
        return [
            'value_is_positive' => $schema->boolean()
                ->description('Whether this proposal applies to incoming (positive) or outgoing (negative) bank transaction amounts.')
                ->required(),

            'text_contains' => $schema->string()
                ->description('A substring to match against the bank transaction description/text to trigger this proposal. Use an exact substring from the bank transaction text and display it in monospace for verification.')
                ->required(),

            'account_proposal' => $schema->integer()
                ->description('The ID of the account to propose for matching transactions.')
                ->required(),

            'text_proposal' => $schema->string()
                ->description('The proposed text to use for matching transactions.')
                ->required(),
        ];
    }
}