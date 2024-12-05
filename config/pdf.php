<?php

return [

    /*
     * Character encoding for the PDF.
     * Commonly set to 'utf-8'.
     */
    'mode' => 'utf-8',

    /*
     * Page size of the PDF document.
     * 'A4' is a standard size used for documents.
     */
    'format' => 'A4',

    /*
     * Orientation of the PDF pages.
     * 'P' for portrait and 'L' for landscape.
     */
    'orientation' => 'P',

    /*
     * Default font size for the PDF text.
     */
    'default_font_size' => '12',

    /*
     * Default font family for the PDF text.
     * 'sans-serif' is a standard choice.
     */
    'default_font' => 'sans-serif',

    /*
     * Margins (in mm) around the content of the PDF.
     */
    'margin_left' => 10,
    'margin_right' => 10,
    'margin_top' => 40,
    'margin_bottom' => 10,
    'margin_header' => 0,
    'margin_footer' => 0,

    /*
     * Metadata for the PDF document.
     */
    'title' => 'Laravel Pdf',
    'subject' => '',
    'author' => '',

    /*
     * Display mode for the PDF when opened.
     * 'fullpage' means the document will fill the entire window.
     *
     * Supported: "fullpage", "fullwidth", "real", "default", "none",
     */
    'display_mode' => 'fullpage',

    /*
     * Watermark image settings.
     */
    'watermark_image_path' => base_path('resources/images/watermark.gif'),
    'watermark_image_alpha' => 1, // Transparency level for the watermark image.
    'watermark_image_size' => 'D', // Size of the watermark image.
    'watermark_image_position' => 'P', // Position of the watermark image.
    'show_watermark_image' => true, // Show or hide the watermark image.

    /*
     * Text watermark settings.
     */
    'watermark' => '', // Text to be used as a watermark.
    'show_watermark' => false, // Show or hide the text watermark.
    'watermark_text_alpha' => 0.1, // Transparency level for the text watermark.
    'watermark_font' => 'sans-serif', // Font for the watermark text.

    /*
     * Custom font settings.
     */
    'custom_font_dir' => base_path('resources/fonts/'), // Directory path for custom fonts (ensure trailing slash).
    'custom_font_data' => [
        'bengali' => [ // Custom font settings for Bengali.
            'R' => 'SolaimanLipi.ttf', // Regular font file.
            'B' => 'SolaimanLipi_Bold.ttf', // Bold font file.
            'useOTL' => 0xFF, // OTL support.
            'useKashida' => 75 // Kashida width.
        ]
    ],

    /*
     * Temporary directory for storing PDF files during generation.
     */
    'temp_dir' => storage_path('app'),

    /*
     * PDF/A compliance settings.
     */
    'pdfa' => false, // Enable PDF/A compliance.
    'pdfaauto' => false, // Automatically apply PDF/A settings.

    /*
     * PDF/X compliance settings.
     */
    'pdfx' => false, // Enable PDF/X compliance.
    'pdfxauto' => false, // Automatically apply PDF/X settings.

    /*
     * Enable interactive forms in the PDF.
     * Allows users to fill out form fields directly within the PDF.
     */
    'use_active_forms' => false,
];
