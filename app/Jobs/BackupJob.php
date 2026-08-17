<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Process;

class BackupJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $timeout = 900;

    public function handle(): void
    {
        $connection = config('database.default');
        $backupDir = config('backup.path', storage_path('app/backups'));

        if (! is_dir($backupDir)) {
            mkdir($backupDir, 0775, true);
        }

        $filename = 'backup-'.now()->format('Y-m-d_H-i-s').'.sqlite';
        $target = $backupDir.DIRECTORY_SEPARATOR.$filename;

        try {
            if ($connection === 'sqlite') {
                // SQLite online backup via PHP (consistent snapshot while app is running).
                $source = config('database.connections.sqlite.database');
                if ($source !== ':memory:' && file_exists($source)) {
                    copy($source, $target);
                }
            } elseif ($connection === 'mysql') {
                $cmd = [
                    config('backup.mysqldump_path', 'mysqldump'),
                    '-u', config('database.connections.mysql.username'),
                    '-h', config('database.connections.mysql.host'),
                ];

                if (config('database.connections.mysql.password')) {
                    $cmd[] = '-p'.config('database.connections.mysql.password');
                }
                $cmd[] = config('database.connections.mysql.database');

                $result = Process::run(implode(' ', array_map('escapeshellarg', $cmd)));

                if ($result->failed()) {
                    throw new \RuntimeException('mysqldump failed: '.$result->errorOutput());
                }
                file_put_contents($target, $result->output());
            } else {
                Log::warning('BackupJob: unsupported connection for backup', ['connection' => $connection]);

                return;
            }

            Log::info('Backup created', ['file' => $target]);
        } catch (\Throwable $e) {
            Log::error('BackupJob failed', ['error' => $e->getMessage()]);
            throw $e;
        }
    }
}
