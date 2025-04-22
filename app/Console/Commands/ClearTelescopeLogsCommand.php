<?php

namespace App\Console\Commands;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ClearTelescopeLogsCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected string $signature = 'telescope:clear-logs';

    /**
     * The console command description.
     *
     * @var string
     */
    protected string $description = 'Clear Telescope logs from the database';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        // Clear Telescope entries
        $connection = DB::connection(config('telescope.storage.database.connection'));
        $connection->table('telescope_entries')->truncate();
        $connection->table('telescope_entries_tags')->truncate();
        $connection->table('telescope_monitoring')->truncate();

        Log::info('Telescope logs have been cleared.');
    }
}
