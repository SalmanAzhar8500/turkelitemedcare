<?php

namespace App\Console\Commands;

use App\Services\ContentPackService;
use Illuminate\Console\Command;

class ImportContentPack extends Command
{
    protected $signature = 'site:factory:import
        {--content=content/en : Content pack folder}
        {--prune : Delete managed content records not present in the pack}
        {--validate-only : Validate without writing anything}';

    protected $description = 'Upsert the complete structured Turkelite Medcare content pack into the database.';

    public function handle(ContentPackService $service): int
    {
        $path = (string) $this->option('content');

        if ($this->option('validate-only')) {
            $result = $service->validate($path);
            foreach ($result['warnings'] as $warning) {
                $this->warn($warning);
            }
            foreach ($result['errors'] as $error) {
                $this->error($error);
            }
            return $result['errors'] === [] ? self::SUCCESS : self::FAILURE;
        }

        try {
            $result = $service->import($path, (bool) $this->option('prune'));
        } catch (\Throwable $e) {
            $this->error($e->getMessage());
            return self::FAILURE;
        }

        $this->table(
            ['Imported dataset', 'Records'],
            collect($result['counts'])->map(fn (int $count, string $name): array => [$name, $count])->values()->all()
        );

        foreach ($result['warnings'] as $warning) {
            $this->warn($warning);
        }

        $this->info('Content import complete. Re-running this command updates existing records instead of duplicating them.');
        return self::SUCCESS;
    }
}
