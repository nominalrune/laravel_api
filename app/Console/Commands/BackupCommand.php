<?php

namespace App\Console\Commands;
use Illuminate\Support\Facades\Schema;

trait BackupCommand
{
    protected function getAllTables()
    {
        $dbName = 'Tables_in_' . env('DB_DATABASE');
        $tables = Schema::getAllTables();
        return array_map(fn ($table) => $table->$dbName, $tables);
    }
    /**
     * List all backups
     */
    protected function listBackups()
    {
        $files = scandir(storage_path() . '/app/backup');
        $files = array_filter($files, fn ($file) => preg_match('/backup-\d{14}.sql.gz/', $file));
        return array_map(fn ($file) => storage_path() . '/app/backup/' . $file, $files);
    }
}
