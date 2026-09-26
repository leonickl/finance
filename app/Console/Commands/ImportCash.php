<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\Account;
use App\Models\CashImport;
use App\Models\Transaction;
use App\Types\Currency;
use App\Types\Date\Date;
use App\Types\Money;
use ICal\ICal;
use Illuminate\Console\Command;

use function Laravel\Prompts\confirm;
use function Laravel\Prompts\search;
use function Laravel\Prompts\suggest;
use function Laravel\Prompts\text;

final class ImportCash extends Command
{
    protected $signature = 'app:import-cash {file} {--dry}';

    protected $description = 'Command description';

    public function handle(): void
    {
        if ($this->option('dry')) {
            $this->table(['uid', 'date', 'text'], $this->todos()->map(fn ($todo) => [
                'uid' => mb_substr($todo->uid, 0, 10) . '...',
                'date' => $todo->date,
                'value' => $todo->value,
                'currency' => $todo->currency,
                'text' => $todo->text,
            ]));

            return;
        }

        $accounts = Account::all();

        $cash_account_id = search(
            label: 'Target Account',
            options: fn (string $value) => mb_strlen($value) > 0
                ? $accounts
                    ->filter(fn ($account) => str_contains(mb_strtolower($account->fullname), mb_strtolower($value)))
                    ->pluck('fullname', 'id')
                    ->toArray()
                : $accounts
                    ->pluck('fullname', 'id')
                    ->toArray(),
        );

        $cash_account = Account::find((int) $cash_account_id);

        $currencies = Transaction::pluck('currency')
            ->unique()
            ->map(fn ($currency) => $currency->code())
            ->values();

        $this->newLine(2);

        foreach ($this->todos() as $todo) {
            if (CashImport::query()->where('uid', $todo->uid)->exists()) {
                $this->line("Skipped existing ({$todo->uid})");
                $this->newLine();

                continue;
            }

            // currency -> or edit
            // debit account: geldbeutel -> or enter
            // credit account: geldbeutel -> or enter

            $this->info($todo->raw);

            $proceed = confirm('Proceed with this record?');

            if ( ! $proceed) {
                continue;
            }

            $value = text(
                label: 'Value',
                default: $todo->value,
                required: true,
                validate: fn (string $value) => preg_match('/^-?\d+([.,]\d{1,2})?$/', $value)
                    ? null
                    : 'Please enter a valid number with up to 2 decimal places.'
            );

            $value = (float) number_format(
                (float) str_replace(',', '.', $value),
                2,
                '.',
                ''
            );

            $currency = suggest(
                label: 'Currency',
                options: $currencies,
                default: mb_strtoupper(str_replace('€', 'EUR', $todo->currency)),
                validate: fn (string $value) => mb_strlen($value) === 3
                    ? null
                    : 'Please enter a valid currency code.',
            );

            $text = text('Text', default: $todo->text, required: true);

            $debit_id = search(
                label: 'Debit Account',
                options: fn (string $value) => mb_strlen($value) > 0
                    ? $accounts
                        ->filter(fn ($account) => str_contains(mb_strtolower($account->fullname), mb_strtolower($value)))
                        ->pluck('fullname', 'id')
                        ->toArray()
                    : [$cash_account->id => $cash_account->fullname],
            );

            $credit_id = search(
                label: 'Credit Account',
                options: fn (string $value) => mb_strlen($value) > 0
                    ? $accounts
                        ->filter(fn ($account) => str_contains(mb_strtolower($account->fullname), mb_strtolower($value)))
                        ->pluck('fullname', 'id')
                        ->toArray()
                    : [$cash_account->id => $cash_account->fullname],
            );

            $transaction = Transaction::create(
                debit: Account::find($debit_id),
                credit: Account::find($credit_id),
                value: Money::new($value, Currency::new($currency)),
                text: $text,
                date: Date::fromDashed($todo->date),
            );

            $cash_import = CashImport::create([
                'uid' => $todo->uid,
                'raw' => $todo->raw,
                'transaction_id' => $transaction->id,
            ]);

            $this->line("Created transaction {$transaction->id} and noted imported record {$cash_import->id}.");
            $this->newLine();
        }
    }

    private function todos()
    {
        return collect(new ICal($this->argument('file'))->cal['VTODO'])
            ->map(function (array $todo) {
                $uid = $todo['UID'];

                if ( ! $uid) {
                    $this->error('no uid found');
                    exit;
                }

                $date = @$todo['DUE'] ?: mb_substr($todo['CREATED'], 0, 8);
                $date = mb_substr($date, 0, 4) . '-' . mb_substr($date, 4, 2) . '-' . mb_substr($date, 6, 2);

                $summary = $todo['SUMMARY'];

                $value = '';
                $currency = '';
                $text = '';

                for ($i = 0; $i < mb_strlen($summary); $i++) {
                    if ($text === '' && $summary[$i] === '\\') {
                        continue;
                    }

                    if ($text === '' && str_contains('+-1234567890,.\\', $summary[$i])) {
                        $value .= $summary[$i];

                        continue;
                    }

                    if ($text === '' && $summary[$i] === ' ') {
                        continue;
                    }

                    $text .= $summary[$i];

                    if (in_array(mb_strtolower($text), ['€', 'eur'])) {
                        $currency = $text;
                        $text = '';
                    }
                }

                $raw = "{$date} - {$summary}";

                return (object) compact('uid', 'date', 'value', 'currency', 'text', 'summary', 'raw');
            });
    }
}
