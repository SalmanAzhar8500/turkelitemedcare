<?php

use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function (): void {
    $this->comment('Build something useful.');
})->purpose('Display an inspiring quote');
