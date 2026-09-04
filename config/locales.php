<?php

$supported = array_values(array_filter(array_map(
    static fn (string $locale): string => strtolower(trim($locale)),
    explode(',', (string) env('SITE_LOCALES', 'en,de,ar'))
)));

if ($supported === []) {
    $supported = ['en'];
}

return [
    'supported' => $supported,
    'names' => [
        'en' => 'English',
        'de' => 'Deutsch',
        'tr' => 'Türkçe',
        'ar' => 'العربية',
    ],
    'rtl' => ['ar'],
];
