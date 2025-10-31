<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class BackupDatabase extends Command
{
    protected $signature = 'db:backup';
    protected $description = 'Backup the database';

    public function handle()
    {
        // Set filename with timestamp
        $filename = 'backup-' . Carbon::now()->format('Y-m-d-H-i-s') . '.sql';

        // Backup path
        $storage_path = Storage::disk('local')->path('backups');

        // Create backups directory if it doesn't exist
        if (!file_exists($storage_path)) {
            mkdir($storage_path, 0755, true);
        }

        // Get database configuration
        $host = config('database.connections.pgsql.host');
        $port = config('database.connections.pgsql.port');
        $database = config('database.connections.pgsql.database');
        $username = config('database.connections.pgsql.username');
        $password = config('database.connections.pgsql.password');

        // Set PGPASSWORD environment variable
        putenv("PGPASSWORD=$password");

        // Backup command
        $command = "pg_dump -h $host -p $port -U $username -d $database -F p > " .
                  storage_path("app/backups/$filename");

        // Execute backup
        $returnVar = NULL;
        $output = NULL;
        exec($command, $output, $returnVar);

        // Remove password from environment
        putenv("PGPASSWORD");

        if ($returnVar === 0) {
            $this->info('Database backup completed successfully.');

            // Keep only last 7 backups
            $files = glob(storage_path("app/backups/*.sql"));
            if (count($files) > 7) {
                array_map('unlink', array_slice($files, 0, -7));
            }
        } else {
            $this->error('Database backup failed.');
        }
    }
}
