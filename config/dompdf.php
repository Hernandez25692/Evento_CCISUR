<?php
return [

    'font_dir' => base_path('storage/fonts'),
    'font_cache' => storage_path('fonts/cache'),

    'default_font' => 'sans-serif',

    'custom_font_dir' => base_path('resources/fonts/VisbyCF'), // <-- TU DIRECTORIO

    'custom_font_data' => [
        'VisbyCF-Bold' => [
            'R'  => 'VisbyCF-Bold.otf',
        ],
        'VisbyCF-BoldOblique' => [
            'R' => 'VisbyCF-BoldOblique.otf',
        ],
        'VisbyCF-RegularOblique' => [
            'R' => 'VisbyCF-RegularOblique.otf',
        ],
        // ... y así con todas
    ],

    'isHtml5ParserEnabled' => true,
    'isRemoteEnabled' => true,

];
