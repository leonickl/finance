<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class Export extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:export';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $tables = [
            'accounts',
            'bank_accounts',
            'bank_proposals',
            'bank_transactions',
            'ibans',
            'people',
            'recurring',
            'settings',
            'streaks',
            'transactions',
        ];

        $sql = '';

        foreach ($tables as $table) {
            $records = DB::query()->select('*')->from($table)->get();

            dump(count($records));

            foreach ($records as $record) {
                $contents = implode(', ', array_map(json_encode(...), (array) $record));
                $columns = implode(', ', array_map(fn (string $value) => '`'.$value.'`', array_keys((array) $record)));

                $sql .= 'insert into `'.$table.'` ('.$columns.') values ('.$contents.');'.PHP_EOL;
            }
        }

        $filename = 'export/export-'.date('Y-m-d-H-i-s').'.sql';
        $result = Storage::put($filename, $sql);

        if ($result) {
            $this->info('created backup file '.$filename);
        } else {
            $this->error('could not create backup file '.$filename);
        }
    }
}
