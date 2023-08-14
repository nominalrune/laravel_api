<?php
declare(strict_types=1);

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ExportAsJson extends Command
{
    use JsonCommand;
    protected $signature = 'db:export {--table=*}';
    protected $description = 'export tables as json';
    public function handle()
    {
        if ($this->hasOption('table')&& count($this->option('table'))>0) {
            $tables = $this->option('table');
            if (! $this->checkIfTableExists($tables)) {
                $this->info('Export canceled.');
                return;
            }
            $this->export($tables);
            $this->info('Export finished.');
            return;
        }
        if ($this->confirm('Do you want to export all tables?')) {
            $this->info('Exporting all tables');
            $tables = $this->getAllTables();
            $this->export($tables);
            return;
        } else {
            $ans = $this->choice(
                'Which table do you want to export?',
                ['cancel export', ...$this->getAllTables()],
                null,
                null,
                true
            );
            if (in_array('cancel export', $ans)) {
                $this->info('Export canceled.');
                return;
            } else {
                $this->export($ans);
                return;
            }
        }
    }
    protected function export(array $tables)
    {
        $this->makeBackupDir();
        $success = [];
        $failed = [];
        foreach ($tables as $table) {
            $filename = $table . '-' . Carbon::now()->format('YmdHis') . '.json';
            try {
                $this->exportTable($table, $filename);
            } catch (\Exception $e) {
                $failed[] = $table;
                Log::channel('backup')->error('Failed to export: ' . $table, ['exception' => $e]);
                continue;
            }
            $success[] = $table;
            Log::channel('backup')->info('Exported: ' . $table . ' as ' . $filename);
        }
        $this->info('Export finished. ' . count($success) . ' tables exported, ' . count($failed) . ' tables failed.'. PHP_EOL . 'Success: ' . implode(', ', $success) . PHP_EOL . 'Failed: ' . implode(', ', $failed));
        return [
            'success' => $success,
            'failed' => $failed,
        ];
    }
    /**
     * Export given table as json
     * @throws \Exception
     */
    protected function exportTable(string $table, string $filename)
    {
        $data = DB::table($table)->get();
        $json = $data->toJson(JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_BIGINT_AS_STRING | JSON_OBJECT_AS_ARRAY); //json_encode($data, );
        $data = null;
        $res = file_put_contents(storage_path() . '/app/backup/' . $filename, $json);
        $json = null;
        if ($res === false) {
            $this->error('Failed to export: ' . $table);
            Log::channel('backup')->error('Failed to export: ' . $table);
            throw new \Exception('Failed to export: ' . $table);
        }
        $this->info('Exported: ' . $table);
    }

    protected function makeBackupDir()
    {
        if (! file_exists(storage_path() . '/app/backup')) {
            mkdir(storage_path() . '/app/backup');
        }
    }
}
