<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class Export extends Command
{
    protected $signature = 'db:export {--mysql} {--sqlite}';

    protected $description = 'Export tables into either MySQL or SQLite insert statements';

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

        // Check options
        $isMysql = $this->option('mysql');
        $isSqlite = $this->option('sqlite');

        if (! $isMysql && ! $isSqlite) {
            $this->error('Please specify either --mysql or --sqlite');

            return Command::FAILURE;
        }

        // Start SQL dump
        if ($isMysql) {
            $sql = "SET FOREIGN_KEY_CHECKS = 0;\n\n";
        } else {
            $sql = "PRAGMA foreign_keys = OFF;\n\nBEGIN TRANSACTION;\n\n";
        }

        foreach ($tables as $table) {
            $records = DB::table($table)->get();
            $this->info("Exporting {$table}: ".$records->count().' rows');

            foreach ($records as $record) {
                $row = (array) $record;

                if ($isMysql) {
                    $columns = implode(', ', array_map(fn ($c) => "`$c`", array_keys($row)));
                } else {
                    $columns = implode(', ', array_map(fn ($c) => "\"$c\"", array_keys($row)));
                }

                $values = implode(', ', array_map(function ($value) {
                    if (is_null($value)) {
                        return 'NULL';
                    }
                    if (is_numeric($value)) {
                        return $value;
                    }
                    // escape quotes
                    $escaped = str_replace("'", "''", $value);

                    return "'".$escaped."'";
                }, array_values($row)));

                $sql .= 'INSERT INTO '
                      .($isMysql ? "`$table`" : "\"$table\"")
                      ." ($columns) VALUES ($values);\n";
            }
            $sql .= "\n";
        }

        // Footer
        if ($isMysql) {
            $sql .= "SET FOREIGN_KEY_CHECKS = 1;\n";
        } else {
            $sql .= "COMMIT;\nPRAGMA foreign_keys = ON;\n";
        }

        // Save file
        $filename = 'export/export-'.date('Y-m-d-H-i-s').($isMysql ? '-mysql.sql' : '-sqlite.sql');
        $result = Storage::put($filename, $sql);

        if ($result) {
            $this->info('Created backup file '.$filename);

            return Command::SUCCESS;
        } else {
            $this->error('Could not create backup file '.$filename);

            return Command::FAILURE;
        }
    }
}
