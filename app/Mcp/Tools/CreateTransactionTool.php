<?php

namespace App\Mcp\Tools;

use App\Models\Transaction;
use App\Types\Date\Date;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Illuminate\Support\Carbon;
use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Tool;

#[Description('Create a transaction. For active accounts, debit receives value, credit gives value.'
    .'For passive accounts, the other way round. For an expense (e.g. ice cream paid in cash):'
    .'debit = expense account, credit = cash account.')]
class CreateTransactionTool extends Tool
{
    /**
     * Handle the tool request.
     */
    public function handle(Request $request): Response
    {
        $validated = $request->validate([
            'debit_id' => ['required', 'integer'],
            'credit_id' => ['required', 'integer'],
            'value' => ['required', 'numeric'],
            'currency' => ['required', 'string'],
            'text' => ['required', 'string'],
            'date' => ['nullable', 'date'],
        ]);

        $transaction = new Transaction;
        $transaction->debit_id = $validated['debit_id'];
        $transaction->credit_id = $validated['credit_id'];
        $transaction->value = $validated['value'];
        $transaction->currency = $validated['currency'];
        $transaction->text = $validated['text'];
        $transaction->date = Date::of(
            $request->filled('date')
                ? Carbon::parse($validated['date'])
                : Carbon::now()
        );
        $transaction->save();

        return Response::text("VERBATIM: Created transaction {$transaction->id}: {$transaction->debit->fullname} to {$transaction->credit->fullname} ".$transaction->value());
    }

    /**
     * Get the tool's input schema.
     *
     * @return array<string, JsonSchema>
     */
    public function schema(JsonSchema $schema): array
    {
        return [
            'debit_id' => $schema->integer()
                ->description('Debit account ID. Asset account (cash/bank/expenses): an increase/inflow. Liability account (revenue/equity): a decrease/outflow.')
                ->required(),

            'credit_id' => $schema->integer()
                ->description('Credit account ID. Asset account (cash/bank/expenses): a decrease/outflow. Liability account (revenue/equity): an increase/inflow.')
                ->required(),

            'value' => $schema->number()
                ->description('The transaction amount.')
                ->required(),

            'currency' => $schema->string()
                ->description('The ISO 4217 currency code, e.g. EUR, USD.')
                ->required(),

            'text' => $schema->string()
                ->description('A description or memo for the transaction.')
                ->required(),

            'date' => $schema->string()
                ->description('The transaction date (YYYY-MM-DD). Defaults to now if omitted.'),
        ];
    }
}