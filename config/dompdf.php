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

        // OJO: este valor no es solo "calidad de imagen" — Dompdf lo usa
        // para convertir TODO valor en px (font-size, x/y, etc.) a puntos
        // del PDF: pt = px * 72 / dpi. Todas las plantillas ya guardadas
        // (font_size, posiciones) fueron calibradas visualmente contra 96,
        // que es el valor con el que Dompdf corrió siempre en este proyecto
        // hasta ahora (la config vieja tenía esta clave fuera de 'options',
        // así que nunca se aplicaba). Subirlo aquí desconfigura de golpe el
        // tamaño/posición de cada plantilla existente. Si en el futuro se
        // quiere más nitidez, se resuelve subiendo la resolución de la
        // imagen de fondo, no este valor.
        'dpi' => 96,

        'enable_php' => false,
        'enable_javascript' => true,
        'enable_remote' => true,
        'allowed_remote_hosts' => null,
        'font_height_ratio' => 1.1,
        'enable_html5_parser' => true,
    ],

];
