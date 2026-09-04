<?php

namespace App\Console\Commands;

use App\Services\ContentPackService;
use Illuminate\Console\Command;

class ValidateContentPack extends Command
{
    protected $signature = 'site:factory:validate {--content=content/en : Content pack folder} {--strict : Treat warnings as failure}';

    protected $description = 'Validate the Turkelite Medcare content pack before touching the database.';

    public function handle(ContentPackService $service): int
    {
        $result = $service->validate((string) $this->option('content'));

        $this->info('Content pack: '.$result['path']);
        $this->table(
            ['Dataset', 'Records'],
            collect($result['counts'])->map(fn (int $count, string $name): array => [$name, $count])->values()->all()
        );

        foreach ($result['warnings'] as $warning) {
            $this->warn($warning);
        }
        foreach ($result['errors'] as $error) {
            $this->error($error);
        }

        if ($result['errors'] !== []) {
            $this->error('Validation failed. Nothing was imported.');
            return self::FAILURE;
        }

        if ($this->option('strict') && $result['warnings'] !== []) {
            $this->error('Strict validation failed because warnings were found.');
            return self::FAILURE;
        }

        $this->info('Validation passed. Database import is safe to run.');
        return self::SUCCESS;
    }
}
