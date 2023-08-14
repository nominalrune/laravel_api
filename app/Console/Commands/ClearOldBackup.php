<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;

class ClearOldBackup extends Command
{
    use BackupCommand;
    protected $signature = 'db:clear-old-backup';
    protected $description = '古いバックアップをwebサーバーから削除します';
    /**
     * Delete old backups
     */
    public function handle()
    {
        // get files
        $oldFiles = $this->listOldBackups(14);
        if (count($oldFiles) === 0) {
            $this->info('No old backups found');
            return;
        }
        // delete files
        $this->info("Deleting old backups... ${count($oldFiles)}" . PHP_EOL . implode(PHP_EOL, $oldFiles));
        foreach ($oldFiles as $file) {
            $this->deleteFile($file);
        }
        Log::channel('backup')->info("Deleted old backups(${count($oldFiles)} files): " . implode(', ', $oldFiles));
    }

    /**
     * tell if it's older than given days (but not 1st of the month)
     */
    private function isOld(string $filename, int $days)
    {
        $today = Carbon::today();
        $filename = basename($filename);
        $filename = str_replace('backup-', '', $filename);
        $filename = str_replace('.sql.gz', '', $filename);
        $fileDate = Carbon::createFromFormat('YmdHis', $filename);
        $diff = $today->diffInDays($fileDate);
        return $diff > $days && $fileDate->day !== 1;
    }
    /**
     * List only old backups
     */
    private function listOldBackups(int $days)
    {
        $files = $this->listBackups();
        return array_filter($files, fn ($file) => $this->isOld($file, $days));
    }

    private function deleteFile($file)
    {
        if (! file_exists($file)) {
            $this->error('File not found: ' . $file);
            return;
        }
        $result = unlink($file);
        if ($result) {
            $this->info('Deleted: ' . $file);
        } else {
            $this->error('Failed to delete: ' . $file);
        }
    }
}
