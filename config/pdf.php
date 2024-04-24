<?php

return [
    'mode'                     => '',
    'format'                   => 'A4',
    'default_font_size'        => '8.5',
    'default_font'             => 'sans-serif',
    'margin_left'              => 8,
    'margin_right'             => 8,
    'margin_top'               => 8,
    'margin_bottom'            => 8,
    'margin_header'            => 0,
    'margin_footer'            => 0,
    'orientation'              => 'P',
    'title'                    => 'BPR NUSAMBA ADIWERNA',
    'subject'                  => '',
    'author'                   => '',
    'watermark'                => '',
    'show_watermark'           => false,
    'show_watermark_image'     => false,
    'watermark_font'           => 'sans-serif',
    'display_mode'             => 'fullpage',
    'watermark_text_alpha'     => 0.1,
    'watermark_image_path'     => '',
    'watermark_image_alpha'    => 0.2,
    'watermark_image_size'     => 'D',
    'watermark_image_position' => 'P',
    'custom_font_dir'          => base_path('resources/fonts/'),
    'custom_font_data'         => [
        'calibri' => [ 
            'R'  => 'calibri-regular.ttf',    // regular font
            'B'  => 'calibri-bold.ttf',       // optional: bold font
            'I'  => 'calibri-italic.ttf',     // optional: italic font
            'BI' => 'calibri-bold-italic.ttf' // optional: bold-italic font
        ]
    ],
    'auto_language_detection'  => false,
    'temp_dir'                 => public_path('mpdf/temp'),
    'pdfa'                     => false,
    'pdfaauto'                 => false,
    'use_active_forms'         => false,
];
