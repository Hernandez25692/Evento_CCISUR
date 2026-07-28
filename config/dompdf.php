<?php

return [

    'show_warnings' => false,

    'public_path' => null,

    'convert_entities' => true,

    'options' => [
        'font_dir' => storage_path('fonts'),
        'font_cache' => storage_path('fonts'),
        'temp_dir' => sys_get_temp_dir(),

        // Debe incluir la carpeta del proyecto donde viven las fuentes
        // personalizadas (resources/fonts) y las imágenes de las plantillas
        // (storage/app/public); sin esto Dompdf rechaza silenciosamente
        // cualquier archivo local fuera de vendor/dompdf/dompdf.
        'chroot' => realpath(base_path()),

        'allowed_protocols' => [
            'data://' => ['rules' => []],
            'file://' => ['rules' => []],
            'http://' => ['rules' => []],
            'https://' => ['rules' => []],
        ],

        'artifactPathValidation' => null,
        'log_output_file' => null,
        'enable_font_subsetting' => true,
        'pdf_backend' => 'CPDF',
        'default_media_type' => 'screen',
        'default_paper_size' => 'letter',
        'default_paper_orientation' => 'portrait',
        'default_font' => 'sans-serif',

        // Sube la resolución de rasterizado (por defecto Dompdf usa 96) para
        // que las plantillas de diploma no se vean borrosas al exportar.
        'dpi' => 200,

        'enable_php' => false,
        'enable_javascript' => true,
        'enable_remote' => true,
        'allowed_remote_hosts' => null,
        'font_height_ratio' => 1.1,
        'enable_html5_parser' => true,
    ],

];
