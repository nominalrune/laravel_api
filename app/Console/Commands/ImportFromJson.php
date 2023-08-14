<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

class ImportFromJson extends Command
{
    use JsonCommand, ValidateWithColumnType;
    protected $signature = 'db:import {--table=*} {--all}';
    protected $description = 'import tables from json';
    public function handle()
    {
        if ($this->option('all')) {
            $this->importAll();
            return;
        }
        if ($this->hasOption('table') && count($this->option('table')) > 0) {
            $tables = $this->option('table');
            if (! $this->checkIfTableExists($tables)) {
                $this->warn('Import canceled.');
                return;
            }
            $this->import($tables);
            $this->info('Import finished.');
            return;
        }
        $this->warn('Please specify --table or --all option.');
    }
    protected function import(array $tables)
    {
        $success = [];
        $failed = [];
        $jsonFiles = $this->getExportedFilesForEachTable($tables);
        if (! $this->confirm('Listed files will be imported. Are you sure to continue? ' . PHP_EOL . ' - ' . implode(', ' . PHP_EOL . ' - ', $jsonFiles))) {
            return $this->info('Import canceled.');
        }

        foreach ($jsonFiles as $table => $file) {
            $json = $this->getJson($file);
            $this->insertData($table, json_decode($json, true));

            $success[] = $table;
        }
        $this->info('Import finished.');
        $this->info('Imported: ' . implode(', ', $success));
        $this->error('Failed: ' . implode(', ', $failed));
    }
    protected function importAll()
    {
        $tables = $this->getAllTables();
        if ($this->confirm('Import all tables?')) {
            return $this->import($tables);
        }
        $this->info('Import canceled.');
    }
    protected function getJson(string $file)
    {
        $isAbsolutePath = fn ($file) => strpos($file, '/') === 0;
        $filePath = $isAbsolutePath($file) ? $file : storage_path() . '/app/backup/' . $file;
        return file_get_contents($filePath);
    }
    protected function getExportedFilesForEachTable(array $tables)
    {
        $files = [];
        foreach ($tables as $table) {
            $files[$table] = $this->getNewestFile($this->getExportedFiles($table));
        }
        return $files;
    }
    protected function insertData(string $table, array $data)
    {
        foreach ($data as $item) {
            $this->info('Validating: ' . $table);
            try {
                $this->validate(DB::table($table), $table, $item);
            } catch (\Exception $e) {
                $this->error('Failed to validate: ' . $table, $e->getMessage());
                Log::channel('backup')->error('Failed to validate: ' . $table, ['exception' => $e]);
                throw $e;
            }
        }
        $this->info('Validation finished: ' . $table);
        $this->info('Inserting: ' . $table);
        try {
            // DB::table($table)->truncate();
            DB::table($table)->insert($data);
        } catch (\Exception $e) {
            $this->error('Failed to insert: ' . $table . '. ' . $e->getMessage());
            Log::channel('backup')->error('Failed to insert: ' . $table, ['exception' => $e]);
        }
    }


    protected function getNewestFile(array $files)
    {
        $newest = null;
        foreach ($files as $file) {
            if ($newest === null) {
                $newest = $file;
                continue;
            }
            if ($file > $newest) {
                $newest = $file;
            }
        }
        return $newest;
    }
    protected function getExportedFiles(string $tableName)
    {
        $files = array_filter(scandir(storage_path() . '/app/backup'), fn ($file) => preg_match('/' . $tableName . '-\d{14}.json/', $file));
        return $files;
    }
}
