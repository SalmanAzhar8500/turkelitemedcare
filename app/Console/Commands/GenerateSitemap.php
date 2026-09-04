<?php

namespace App\Console\Commands;

use App\Services\SitemapGenerator;
use Illuminate\Console\Command;

class GenerateSitemap extends Command
{
    protected $signature = 'sitemap:generate';
    protected $description = 'Generate the public sitemap.xml using published Laravel content';

    public function handle(SitemapGenerator $sitemap): int
    {
        $sitemap->write();
        $this->components->info('Generated public/sitemap.xml with clean Laravel routes.');

        return self::SUCCESS;
    }
}
