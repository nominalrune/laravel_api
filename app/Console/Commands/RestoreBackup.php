<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class RestoreBackup extends Command
{
    use BackupCommand;
    protected $signature = 'db:restore {filename? : The full-path of the backup file}';
    protected $description = 'restore a backup file';
    private $filename;
    /**
     * Restore given backup
     */
    public function handle()
    {
        if ($this->argument('filename') === null) {
            $this->filename = $this->choice(
                'Which file do you want to restore?',
                $this->listBackups(),
            );
        } else {
            $this->filename = $this->argument('filename');
        }
        $this->restore($this->filename);
    }

    private function restore(string $filename)
    {
        $command = implode(' ', [
            'gunzip',
            '-c',
            $filename,
            '|',
            'mysql',
            '--user=' . env('DB_USERNAME'),
            '--password=' . env('DB_PASSWORD'),
            '--host=' . env('DB_HOST'),
            env('DB_DATABASE'),
        ]);
        $returnVar = null;
        $output = null;
        exec($command, $output, $returnVar);
        if ($returnVar !== 0) {
            $this->error('Failed to restore: ' . $filename);
            Log::channel('backup')->error('Failed to restore: ' . $filename, ['returnVar' => $returnVar, 'output' => $output]);
            return;
        }
        $this->info('Restored: ' . $filename);
        Log::channel('backup')->info('Restored: ' . $filename);
    }
}
