<?php

namespace Ashik\Pdf;

use Illuminate\Support\Facades\View;
use Mpdf\Mpdf;
use Mpdf\MpdfException;
use Mpdf\Output\Destination;
use Illuminate\Support\Facades\Config;

class PdfWrapper
{
    protected Mpdf $mpdf;
    protected array $config = [];

    /**
     * @throws MpdfException
     */
    public function __construct(array $config = [])
    {
        $this->config = $config;

        $defaultConfig = (new \Mpdf\Config\ConfigVariables())->getDefaults();
        $fontDirs = $defaultConfig['fontDir'];
        $tempDir = $defaultConfig['tempDir'];

        $defaultFontConfig = (new \Mpdf\Config\FontVariables())->getDefaults();
        $fontData = $defaultFontConfig['fontdata'];

        $configGlobal = [
            'mode'              => $this->getConfig('mode'),
            'format'            => $this->getConfig('format'),
            'orientation'       => $this->getConfig('orientation'),
            'default_font_size' => $this->getConfig('default_font_size'),
            'default_font'      => $this->getConfig('default_font'),
            'margin_left'       => $this->getConfig('margin_left'),
            'margin_right'      => $this->getConfig('margin_right'),
            'margin_top'        => $this->getConfig('margin_top'),
            'margin_bottom'     => $this->getConfig('margin_bottom'),
            'margin_header'     => $this->getConfig('margin_header'),
            'margin_footer'     => $this->getConfig('margin_footer'),
            'fontDir'           => array_merge($fontDirs, [
                $this->getConfig('custom_font_dir')
            ]),
            'fontdata'          => array_merge($fontData, $this->getConfig('custom_font_data')),
            'autoScriptToLang'  => $this->getConfig('auto_language_detection'),
            'autoLangToFont'    => $this->getConfig('auto_language_detection'),
            'tempDir'           => ($this->getConfig('temp_dir')) ?: $tempDir,
        ];

        $configMerge = array_merge($configGlobal, $this->config);

        $this->mpdf = new Mpdf(array_merge($defaultConfig, $configMerge));

        $this->mpdf->SetTitle($this->getConfig('title'));
        $this->mpdf->SetSubject($this->getConfig('subject'));
        $this->mpdf->SetAuthor($this->getConfig('author'));

        $this->mpdf->SetWatermarkText($this->getConfig('watermark'));
        $this->mpdf->SetWatermarkImage(
            $this->getConfig('watermark_image_path'), // http://www.yourdomain.com/images/logo.jpg
            $this->getConfig('watermark_image_alpha'), // 1
            $this->getConfig('watermark_image_size'), // ''
            $this->getConfig('watermark_image_position') // [160, 10]
        );
        $this->mpdf->showWatermarkImage = $this->getConfig('show_watermark_image');
        $this->mpdf->showWatermarkText  = $this->getConfig('show_watermark');
        $this->mpdf->watermark_font     = $this->getConfig('watermark_font');
        $this->mpdf->watermarkTextAlpha = $this->getConfig('watermark_text_alpha');

        $this->mpdf->SetDisplayMode($this->getConfig('display_mode'));

        $this->mpdf->PDFA               = $this->getConfig('pdfa') ?: false;
        $this->mpdf->PDFAauto           = $this->getConfig('pdfaauto') ?: false;
        // use active forms
        $this->mpdf->useActiveForms = $this->getConfig('use_active_forms');
    }

    protected function getConfig($key)
    {
        return $this->config[$key] ?? Config::get('pdf.' . $key);
    }

    // Method to load raw HTML
    public function loadHTML($html)
    {
        $this->mpdf->WriteHTML($html);
        return $this;
    }

    // Method to load a Blade view and convert it to HTML
    public function loadView($view, $data = []): static
    {
        $html = View::make($view, $data)->render();
        return $this->loadHTML($html);
    }


    /**
     * @throws MpdfException
     */
    public function output(): string
    {
        return $this->mpdf->Output('', Destination::STRING_RETURN);
    }

    /**
     * @throws MpdfException
     */
    public function save(string $filename): ?string
    {
        return $this->mpdf->Output($filename, Destination::FILE);
    }

    /**
     * @throws MpdfException
     */
    public function download(string $filename = 'document.pdf'): ?string
    {
        $output = $this->mpdf->Output($filename, Destination::DOWNLOAD);

        // Add CORS headers to the PDF response
        return response($output, 200)
            ->header('Content-Type', 'application/pdf')
            ->header('Cache-Control', 'no-cache, must-revalidate')
            ->header('Content-Disposition', 'inline; filename="invoice.pdf"')
            ->header('Access-Control-Allow-Origin', '*') // Allow cross-origin access
            ->header('Access-Control-Allow-Methods', 'GET, POST, OPTIONS')
            ->header('Access-Control-Allow-Headers', 'Origin, Content-Type, Accept')
            ->header('Access-Control-Expose-Headers', 'Content-Disposition'); // Expose content-disposition header for download
    }

    /**
     * @throws MpdfException
     */
    public function stream(string $filename = 'document.pdf'): ?string
    {
        return $this->mpdf->Output($filename, Destination::INLINE);
    }

    /**
     * @throws MpdfException
     */
    public function setPaper($size = 'A4', $orientation = 'P'): static
    {
        $this->mpdf->_setPageSize($size, $orientation);
        return $this;
    }

    // Additional method to set any mPDF configuration options
    public function setOption($key, $value): static
    {
        $this->mpdf->$key = $value;
        return $this;
    }
}
