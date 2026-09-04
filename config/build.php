<?php
return [
    'version' => env('SITE_BUILD', 'FINAL-MGMT'),
    'port' => (int) env('SITE_PORT', 8000),
    'label' => env('SITE_BUILD_LABEL', 'Management Final Build'),
];
