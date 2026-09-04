<?php

namespace App\Console\Commands;

use App\Services\ContentPackService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class RebuildSiteFromContent extends Command
{
    protected $signature = 'site:factory:rebuild
        {--content=content/en : Content pack folder}
        {--yes : Confirm destructive database rebuild}
        {--allow-production : Allow destructive rebuild when APP_ENV=production}';

    protected $description = 'Destroy the current content database, run migrations, seed an admin user, and rebuild from the canonical content pack.';

    public function handle(ContentPackService $service): int
    {
        if (! $this->option('yes')) {
            $this->error('Refusing to erase the database without --yes.');
            $this->line('Use site:factory:import for normal non-destructive updates.');
            return self::FAILURE;
        }

        if (app()->environment('production') && ! $this->option('allow-production')) {
            $this->error('Refusing destructive rebuild in production without --allow-production.');
            return self::FAILURE;
        }

        $validation = $service->validate((string) $this->option('content'));
        foreach ($validation['warnings'] as $warning) {
            $this->warn($warning);
        }
        if ($validation['errors'] !== []) {
            foreach ($validation['errors'] as $error) {
                $this->error($error);
            }
            $this->error('Content validation failed. Database was NOT touched.');
            return self::FAILURE;
        }

        $this->warn('Rebuilding database from migrations and canonical content pack...');

        $exit = Artisan::call('migrate:fresh', ['--force' => true]);
        $this->output->write(Artisan::output());
        if ($exit !== 0) {
            return self::FAILURE;
        }

        $exit = Artisan::call('db:seed', ['--force' => true]);
        $this->output->write(Artisan::output());
        if ($exit !== 0) {
            return self::FAILURE;
        }

        try {
            $result = $service->import((string) $this->option('content'), true);
        } catch (\Throwable $e) {
            $this->error($e->getMessage());
            return self::FAILURE;
        }


        try {
            $exit = Artisan::call('sitemap:generate');
            $this->output->write(Artisan::output());

            if ($exit !== 0) {
                $this->warn('Sitemap generation failed; the database rebuild itself completed successfully.');
            }
        } catch (\Throwable $e) {
            $this->warn('Sitemap generation failed; continuing because the database rebuild completed successfully.');
            $this->line($e->getMessage());
        }

        $this->table(
            ['Rebuilt dataset', 'Records'],
            collect($result['counts'])->map(fn (int $count, string $name): array => [$name, $count])->values()->all()
        );
        $this->info('Database rebuilt from the FINAL multilingual content pack. This build is reproducible.');

        return self::SUCCESS;
    }
}
