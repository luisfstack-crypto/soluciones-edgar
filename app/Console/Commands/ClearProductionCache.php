<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ClearProductionCache extends Command
{
    protected $signature = 'production:clear-cache';

    protected $description = 'Clear Laravel, Filament, and view caches';

    public function handle(): int
    {
        $commands = [
            'optimize:clear',
            'filament:clear-cached-components',
            'view:clear',
        ];

        foreach ($commands as $command) {
            $this->newLine();
            $this->line("Running {$command}...");

            if ($this->call($command) !== self::SUCCESS) {
                $this->error("The {$command} command failed.");

                return self::FAILURE;
            }
        }

        $this->info('Production cache cleared successfully.');

        return self::SUCCESS;
    }
}
