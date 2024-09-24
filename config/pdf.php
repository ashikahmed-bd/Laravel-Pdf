<?php

return [
    'mode'                     => '',
    'format'                   => 'A4',
    'default_font_size'        => '12',
    'default_font'             => 'sans-serif',
    'margin_left'              => 10,
    'margin_right'             => 10,
    'margin_top'               => 50,
    'margin_bottom'            => 10,
    'margin_header'            => 0,
    'margin_footer'            => 0,
    'orientation'              => 'P',
    'title'                    => 'Laravel mPDF',
    'subject'                  => '',
    'author'                   => '',
    'display_mode'             => 'fullpage',

    // watermark for image
    'watermark_image_path'     => base_path('resources/images/watermark.gif'),
    'watermark_image_alpha'    => 1,
    'watermark_image_size'     => 'D',
    'watermark_image_position' => 'P',
    'show_watermark_image'     => true,

    // watermark for text
    'watermark'                => '',
    'show_watermark'           => false,
    'watermark_text_alpha'     => 0.1,
    'watermark_font'           => 'sans-serif',

    'custom_font_dir'  => base_path('resources/fonts/'), // don't forget the trailing slash!
    'custom_font_data' => [
        'bengali' => [ // must be lowercase and snake_case
            'R'  => 'SolaimanLipi.ttf',
            'B'  => 'SolaimanLipi_Bold.ttf',
            'useOTL' => 0xFF,
            'useKashida' => 75
        ]
    ],
    'auto_language_detection'  => false,
    'temp_dir'                 => storage_path('app'),
    'pdfa'                     => false,
    'pdfaauto'                 => false,
    'use_active_forms'         => false,
];
