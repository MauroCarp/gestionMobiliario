<?php

namespace App\Services;

use Illuminate\Support\Facades\File;
use RuntimeException;
use Symfony\Component\Process\Process;

class DatabaseBackupService
{
    /**
     * @return array{ruta: string, archivo: string, bytes: int}
     */
    public function ejecutar(): array
    {
        $config = config('database.connections.mysql');

        if (($config['driver'] ?? null) !== 'mysql') {
            throw new RuntimeException('El backup automático solo está disponible para conexiones MySQL.');
        }

        $binario = $this->resolverBinarioMysqldump();
        $directorio = rtrim(str_replace('\\', '/', config('backup.path')), '/');
        File::ensureDirectoryExists($directorio);

        $archivo = 'gestion_mobiliario_'.now()->format('Y-m-d_H-i').'.sql';
        $ruta = $directorio.'/'.$archivo;

        $process = new Process([
            $binario,
            '--host='.($config['host'] ?? '127.0.0.1'),
            '--port='.($config['port'] ?? '3306'),
            '--user='.($config['username'] ?? 'root'),
            '--single-transaction',
            '--routines',
            '--triggers',
            '--events',
            '--no-tablespaces',
            '--default-character-set=utf8mb4',
            '--result-file='.$ruta,
            $config['database'],
        ], null, null, null, 600);

        $process->run(null, [
            'MYSQL_PWD' => (string) ($config['password'] ?? ''),
        ]);

        if (! $process->isSuccessful()) {
            if (File::exists($ruta)) {
                File::delete($ruta);
            }

            $error = trim($process->getErrorOutput()."\n".$process->getOutput());

            throw new RuntimeException($error !== '' ? $error : 'mysqldump finalizó con error.');
        }

        if (! File::exists($ruta) || File::size($ruta) === 0) {
            if (File::exists($ruta)) {
                File::delete($ruta);
            }

            throw new RuntimeException('El archivo de backup no se generó o está vacío.');
        }

        return [
            'ruta' => $ruta,
            'archivo' => $archivo,
            'bytes' => File::size($ruta),
        ];
    }

    private function resolverBinarioMysqldump(): string
    {
        $configurado = str_replace('\\', '/', config('backup.mysqldump'));

        if ($configurado !== '' && File::exists($configurado)) {
            return $configurado;
        }

        $candidatos = glob('C:/wamp64/bin/mysql/*/bin/mysqldump.exe') ?: [];

        if ($candidatos !== []) {
            return str_replace('\\', '/', $candidatos[0]);
        }

        throw new RuntimeException('No se encontró mysqldump.exe. Configure MYSQLDUMP_PATH en el entorno.');
    }
}
