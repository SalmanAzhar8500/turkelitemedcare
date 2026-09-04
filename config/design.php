<?php

return [
    'default' => env('SITE_THEME', 'clinical'),
    'allowed' => ['clinical'],
    'show_switcher' => filter_var(env('SHOW_CONCEPT_SWITCHER', false), FILTER_VALIDATE_BOOL),
];
