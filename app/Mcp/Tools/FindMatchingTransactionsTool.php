<?php

namespace App\Mcp\Tools;

use App\Models\BankTransaction;
use App\Models\Transaction;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Illuminate\Support\Carbon;
use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\ResponseFactory;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Tool;

#[Description('Find existing transactions that match unresolved bank transactions by amount (exact) and date (within a configurable window, default ±3 days). Shows bank backlinks on matching transactions.')]
class FindMatchingTransactionsTool extends Tool
{
    private const int DEFAULT_DAYS = 3;

    public function handle(Request $request): ResponseFactory
    {
        $bankAccountId = $request->integer('bank_account_id');
        $limit = $request->integer('limit', 10);
        $days = $request->integer('days', self::DEFAULT_DAYS);

        $bankTransactions = BankTransaction::query()
            ->where('bank_account_id', $bankAccountId)
            ->whereNull('transaction_id')
            ->where('skipped', false)
            ->limit($limit)
            ->get();

        $results = [];

        foreach ($bankTransactions as $bankTransaction) {
            $btDate = Carbon::parse($bankTransaction->date);

            $query = Transaction::query()
                ->where('value', abs($bankTransaction->value))
                ->whereBetween('timestamp', [
                    $btDate->copy()->subDays($days)->startOfDay(),
                    $btDate->copy()->addDays($days)->endOfDay(),
                ]);

            if ($bankTransaction->value > 0) {
                $query->where('debit_id', $bankTransaction->bankAccount->account_id);
            } else {
                $query->where('credit_id', $bankTransaction->bankAccount->account_id);
            }

            $query->whereNotIn('id', function ($sub) use ($bankTransaction) {
                $sub->select('transaction_id')
                    ->from('bank_transactions')
                    ->whereNotNull('transaction_id')
                    ->where('value', $bankTransaction->value > 0 ? '>' : '<', '0');
            });

            $matchingTransactions = $query->get();

            if ($matchingTransactions->isEmpty()) {
                continue;
            }

            $transactionsData = $matchingTransactions->map(function (Transaction $transaction) {
                $bankBacklinks = $transaction->bankTransactions()
                    ->whereNotNull('transaction_id')
                    ->get()
                    ->map(fn (BankTransaction $bt) => [
                        'id' => $bt->id,
                        'text' => $bt->text,
                        'date' => $bt->date,
                        'money' => (string) $bt->money,
                        'bank_account_id' => $bt->bank_account_id,
                    ]);

                return [
                    'id' => $transaction->id,
                    'text' => $transaction->text,
                    'date' => $transaction->timestamp->toDateString(),
                    'value' => (float) $transaction->value,
                    'currency' => $transaction->currency->code(),
                    'debit' => $transaction->debit->fullname,
                    'credit' => $transaction->credit->fullname,
                    'bank_backlinks' => $bankBacklinks,
                ];
            });

            $results[] = [
                'bank_transaction' => [
                    'id' => $bankTransaction->id,
                    'text' => $bankTransaction->text,
                    'date' => $bankTransaction->date,
                    'value' => $bankTransaction->value,
                    'money' => (string) $bankTransaction->money,
                ],
                'matching_transactions' => $transactionsData,
            ];
        }

        return Response::structured([
            'matches' => $results,
        ]);
    }

    public function schema(JsonSchema $schema): array
    {
        return [
            'bank_account_id' => $schema->integer()
                ->description('The bank account id to scan for unresolved transactions')
                ->required(),

            'limit' => $schema->integer()
                ->description('Max number of unresolved bank transactions to check (default 10)'),

            'days' => $schema->integer()
                ->description('Date matching window in days (±n days from bank transaction date, default 3)'),
        ];
    }

    public function outputSchema(JsonSchema $schema): array
    {
        return [
            'matches' => $schema->array()
                ->items(
                    $schema->object([
                        'bank_transaction' => $schema->object([
                            'id' => $schema->integer()->description('Bank transaction ID')->required(),
                            'text' => $schema->string()->description('Bank transaction text')->required(),
                            'date' => $schema->string()->description('Bank transaction date')->required(),
                            'value' => $schema->number()->description('Bank transaction raw value')->required(),
                            'money' => $schema->string()->description('Formatted value with currency')->required(),
                        ])->required(),
                        'matching_transactions' => $schema->array()
                            ->items(
                                $schema->object([
                                    'id' => $schema->integer()->description('Transaction ID')->required(),
                                    'text' => $schema->string()->description('Transaction description')->required(),
                                    'date' => $schema->string()->description('Transaction timestamp date')->required(),
                                    'value' => $schema->number()->description('Transaction value')->required(),
                                    'currency' => $schema->string()->description('Currency code')->required(),
                                    'debit' => $schema->string()->description('Debit account fullname')->required(),
                                    'credit' => $schema->string()->description('Credit account fullname')->required(),
                                    'bank_backlinks' => $schema->array()
                                        ->items(
                                            $schema->object([
                                                'id' => $schema->integer()->description('Linked bank transaction ID')->required(),
                                                'text' => $schema->string()->description('Bank transaction text')->required(),
                                                'date' => $schema->string()->description('Bank transaction date')->required(),
                                                'money' => $schema->string()->description('Formatted value')->required(),
                                                'bank_account_id' => $schema->integer()->description('Bank account ID')->required(),
                                            ]),
                                        )
                                        ->description('Bank transactions already linked to this accounting transaction')
                                        ->required(),
                                ]),
                            )
                            ->required(),
                    ]),
                )
                ->required(),
        ];
    }
}
