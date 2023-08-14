<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class BackupDatabase extends Command
{
    use BackupCommand;
    protected $signature = 'db:backup {--table=* : backup only specified tables} {--all : backup all tables}}';
    protected $description = 'make a backup of the database';
    /**
     * Backup database
     */
    public function handle()
    {
        $filename = 'backup-' . Carbon::now()->format('YmdHis') . '.sql.gz';
        if ($this->hasOption('all')) {
            $filename = 'full' . $filename;
            $this->info('Backing up all tables');
            $tables = $this->getAllTables();
        } else {
            $ans = $this->choice(
                'Which table do you want to export?',
                ['cancel export', ...$this->getAllTables()],
                null,
                null,
                true
            );
            if (in_array('cancel backup', $ans)) {
                $this->info('Backup canceled.');
                return;
            } else {
                $tables = $ans;
            }
        }

        $this->info('Backing up: ' . implode(', ', $tables));

        // check if there exits /app/backup directory
        if (! file_exists(storage_path() . '/app/backup')) {
            mkdir(storage_path() . '/app/backup');
        }
        $command = implode(' ', [
            'mysqldump',
            '--no-tablespaces',
            '--single-transaction',
            '--user=' . env('DB_USERNAME'),
            '--password=' . env('DB_PASSWORD'),
            '--host=' . env('DB_HOST'),
            env('DB_DATABASE'),
            implode(' ', $tables),
            '| gzip',
            '>',
            storage_path() . '/app/backup/' . $filename,
        ]);
        $returnVar = null;
        $output = null;
        $this->makeBackupDir();
        exec($command, $output, $returnVar);
        if ($returnVar === 0) {
            $this->info('Backup was successful');
            Log::channel('backup')->info('Backup finished.' . PHP_EOL . "created: ${filename}" . PHP_EOL . 'backup tables: ' . implode(', ', $tables));
        } else {
            $this->error('Backup failed');
            $this->error(implode(PHP_EOL, $output));
            Log::channel('backup')->error('Backup failed.' . PHP_EOL . implode(PHP_EOL, $output));
        }
    }
    protected function checkIfUpdatedToday(string $table)
    {
        $today = Carbon::today();
        $lastUpdated = DB::table($table)->max('updated_at');
        if ($lastUpdated === null) {
            return true;
        }
        $lastUpdated = Carbon::parse($lastUpdated);
        return $today->diffInDays($lastUpdated) > 0;
    }
    private function makeBackupDir()
    {
        //check if there exits /app/backup directory
        if (! file_exists(storage_path() . '/app/backup')) {
            mkdir(storage_path() . '/app/backup');
        }
    }
}
