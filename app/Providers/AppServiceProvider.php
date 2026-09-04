<?php

namespace App\Providers;

use App\Models\SiteSetting;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        $settings = SiteSetting::values();

        if ($settings !== []) {
            config([
                'mail.default' => $settings['mail_mailer'] ?? config('mail.default'),
                'mail.mailers.smtp.host' => $settings['mail_host'] ?? config('mail.mailers.smtp.host'),
                'mail.mailers.smtp.port' => $settings['mail_port'] ?? config('mail.mailers.smtp.port'),
                'mail.mailers.smtp.username' => $settings['mail_username'] ?? config('mail.mailers.smtp.username'),
                'mail.mailers.smtp.scheme' => $settings['mail_encryption'] ?? config('mail.mailers.smtp.scheme'),
                'mail.from.address' => $settings['mail_from_address'] ?? config('mail.from.address'),
                'mail.from.name' => $settings['mail_from_name'] ?? config('mail.from.name'),
            ]);
        }
    }
}
