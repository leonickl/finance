<?php

namespace App\Mcp\Tools;

use App\Models\BankTransaction;
use App\Models\Transaction;
use App\Types\Date\Date;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Illuminate\Support\Carbon;
use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Tool;

#[Description('Create a transaction and link it to a bank transaction. '
    .'This works like create-transaction-tool but also marks the bank transaction as resolved. '
    .'For active accounts, debit receives value, credit gives value. '
    .'For passive accounts, the other way round.')]
class CreateTransactionForBankTransactionTool extends Tool
{
    public function handle(Request $request): Response
    {
        $validated = $request->validate([
            'bank_transaction_id' => ['required', 'integer'],
            'debit_id' => ['required', 'integer'],
            'credit_id' => ['required', 'integer'],
            'value' => ['required', 'numeric'],
            'currency' => ['required', 'string'],
            'text' => ['required', 'string'],
            'date' => ['nullable', 'date'],
        ]);

        $bankTransaction = BankTransaction::findOrFail($validated['bank_transaction_id']);

        if ($bankTransaction->transaction_id !== null) {
            return Response::text("VERBATIM: Bank transaction {$bankTransaction->id} is already linked to transaction {$bankTransaction->transaction_id}.");
        }

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

        $bankTransaction->transaction_id = $transaction->id;
        $bankTransaction->save();

        return Response::text("VERBATIM: Created transaction {$transaction->id} linked to bank transaction {$bankTransaction->id}: {$transaction->debit->fullname} to {$transaction->credit->fullname} ".$transaction->value());
    }

    public function schema(JsonSchema $schema): array
    {
        return [
            'bank_transaction_id' => $schema->integer()
                ->description('The ID of the bank transaction to link to.')
                ->required(),

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
