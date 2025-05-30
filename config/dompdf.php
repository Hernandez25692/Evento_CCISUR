<?php
return [

    'font_dir' => base_path('storage/fonts'),
    'font_cache' => storage_path('fonts/cache'),

    'default_font' => 'sans-serif',

    'custom_font_dir' => base_path('resources/fonts/VisbyCF'),

    'custom_font_data' => [
        'Visby-DemiBold' => [
            'R' => 'VisbyCF-DemiBold.otf',
        ],
        'Visby-Heavy' => [
            'R' => 'VisbyCF-Heavy.otf',
        ],
        'Visby-Light' => [
            'R' => 'VisbyCF-Light.otf',
        ],
    ],


    'isHtml5ParserEnabled' => true,
    'isRemoteEnabled' => true,

];
