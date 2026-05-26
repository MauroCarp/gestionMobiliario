<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class BackupDatabase extends Command
{
    protected $signature   = 'backup:database {--keep=7 : Cantidad de backups a conservar}';
    protected $description = 'Genera un backup de la base de datos MySQL en storage/app/backups';

    public function handle(): int
    {
        $dbConfig = config('database.connections.' . config('database.default'));
        $host     = $dbConfig['host']     ?? '127.0.0.1';
        $port     = $dbConfig['port']     ?? 3306;
        $database = $dbConfig['database'] ?? '';
        $username = $dbConfig['username'] ?? '';
        $password = $dbConfig['password'] ?? '';

        $backupDir = storage_path('app/backups');

        if (! is_dir($backupDir)) {
            mkdir($backupDir, 0755, true);
        }

        $filename = 'backup_' . now()->format('Y_m_d_His') . '.sql';
        $filepath = $backupDir . DIRECTORY_SEPARATOR . $filename;

        // Construir comando mysqldump
        $cmd = sprintf(
            'mysqldump --host=%s --port=%s --user=%s --password=%s %s > %s 2>&1',
            escapeshellarg($host),
            escapeshellarg((string) $port),
            escapeshellarg($username),
            escapeshellarg($password),
            escapeshellarg($database),
            escapeshellarg($filepath)
        );

        exec($cmd, $output, $returnCode);

        if ($returnCode !== 0 || ! file_exists($filepath) || filesize($filepath) === 0) {
            $this->error('El backup falló.');
            if (! empty($output)) {
                $this->line(implode("\n", $output));
            }
            return self::FAILURE;
        }

        $sizeKb = round(filesize($filepath) / 1024, 1);
        $this->info("Backup creado: {$filename} ({$sizeKb} KB)");

        // Rotar: conservar solo los últimos N backups
        $keep  = max(1, (int) $this->option('keep'));
        $files = glob($backupDir . DIRECTORY_SEPARATOR . 'backup_*.sql') ?: [];
        rsort($files); // más nuevo primero

        foreach (array_slice($files, $keep) as $old) {
            unlink($old);
            $this->info('Backup antiguo eliminado: ' . basename($old));
        }

        return self::SUCCESS;
    }
}
