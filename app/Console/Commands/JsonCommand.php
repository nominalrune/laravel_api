<?php

namespace App\Console\Commands;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

trait JsonCommand {
    protected function getAllTables()
    {
        $dbName = 'Tables_in_' . env('DB_DATABASE');
        $tables = Schema::getAllTables();
        return array_map(fn ($table) => $table->$dbName, $tables);
    }
    protected function checkIfTableExists(array $tables)
    {
        $_tables = $this->getAllTables();
        foreach ($tables as $table) {
            if (! in_array($table, $_tables)) {
                $this->error("Table not found: ${table}");
                return false;
            }
        }
        return true;
    }
}
