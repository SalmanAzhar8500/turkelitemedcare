<?php

return [
    // Phase-one commercial pages. These are the pages we want search engines to spend attention on first.
    // The remaining treatment library stays useful for users but is noindex until it has the same editorial depth.
    'priority_procedures' => [
        'fue-hair-transplant',
        'dhi-hair-transplant',
        'dental-implants',
        'all-on-4',
        'dental-veneers',
        'dental-crowns',
        'rhinoplasty',
        'breast-augmentation',
        'breast-lift',
        'liposuction',
        'tummy-tuck-abdominoplasty',
        'blepharoplasty',
        'sleeve-gastrectomy',
        'roux-en-y-gastric-bypass',
        'gastric-balloon',
        'lasik',
        'cataract-surgery',
        'in-vitro-fertilisation-ivf',
        'total-knee-replacement',
        'total-hip-replacement',
    ],
    'indexable_locales' => ['en', 'de', 'ar'],
    'configured_clinic_slugs' => ['clinic-expert'],
];
