<?php

namespace Ashik\Pdf;

use Mpdf\Mpdf;
use Mpdf\MpdfException;
use Mpdf\Output\Destination;
use Illuminate\Support\Facades\Config;

class Pdf
{

    protected $mpdf;
    protected $config = [];

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
            $this->getConfig('watermark_image_path'),
            $this->getConfig('watermark_image_alpha'),
            $this->getConfig('watermark_image_size'),
            $this->getConfig('watermark_image_position')
        );
        $this->mpdf->SetDisplayMode($this->getConfig('display_mode'));

        $this->mpdf->PDFA               = $this->getConfig('pdfa') ?: false;
        $this->mpdf->PDFAauto           = $this->getConfig('pdfaauto') ?: false;
        $this->mpdf->showWatermarkText  = $this->getConfig('show_watermark');
        $this->mpdf->showWatermarkImage = $this->getConfig('show_watermark_image');
        $this->mpdf->watermark_font     = $this->getConfig('watermark_font');
        $this->mpdf->watermarkTextAlpha = $this->getConfig('watermark_text_alpha');
        // use active forms
        $this->mpdf->useActiveForms = $this->getConfig('use_active_forms');
    }

    protected function getConfig($key)
    {
        return $this->config[$key] ?? Config::get('pdf.' . $key);
    }

    /**
     * @throws MpdfException
     */
    public function loadView($view, $data = []): Pdf
    {
        $html = view($view, $data)->render();
        $this->mpdf->WriteHTML($html);
        return $this;
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
    public function save(string $filename)
    {
        return $this->mpdf->Output($filename, Destination::FILE);
    }

    /**
     * @throws MpdfException
     */
    public function download(string $filename = 'document.pdf')
    {
        return $this->mpdf->Output($filename, Destination::DOWNLOAD);
    }

    /**
     * @throws MpdfException
     */
    public function stream(string $filename = 'document.pdf')
    {
        return $this->mpdf->Output($filename, Destination::INLINE);
    }
}
