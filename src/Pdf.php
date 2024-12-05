<?php

namespace Ashik\Pdf;

use Mpdf\Config\ConfigVariables;
use Mpdf\Config\FontVariables;
use Mpdf\Mpdf;
use Mpdf\MpdfException;
use Mpdf\Output\Destination;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\View;
use Symfony\Component\HttpFoundation\Response;

class Pdf
{
    protected Mpdf $mpdf;
    protected array $config = [];
    protected string $filename = 'document.pdf';

    /**
     * @throws MpdfException
     */
    public function __construct(array $config = [])
    {
        $this->config = $config;

        $defaultConfig = (new ConfigVariables())->getDefaults();
        $fontDirs = $defaultConfig['fontDir'];
        $defaultFontConfig = (new FontVariables())->getDefaults();
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

            'fontDir'           => array_merge($fontDirs, [$this->getConfig('custom_font_dir')]),
            'fontdata'          => array_merge($fontData, $this->getConfig('custom_font_data')),
            'tempDir'           => $this->getConfig('temp_dir') ?: $defaultConfig['tempDir'],
        ];

        $this->mpdf = new Mpdf(array_merge($defaultConfig, $configGlobal));

        // Basic configuration for PDF metadata and watermark
        $this->mpdf->SetTitle($this->getConfig('title'));
        $this->mpdf->SetSubject($this->getConfig('subject'));
        $this->mpdf->SetAuthor($this->getConfig('author'));
        $this->mpdf->SetDisplayMode($this->getConfig('display_mode', 'fullpage'));


        $this->mpdf->SetWatermarkText($this->getConfig('watermark'));
        $this->mpdf->SetWatermarkImage(
            $this->getConfig('watermark_image_path'),
            $this->getConfig('watermark_image_alpha'),
            $this->getConfig('watermark_image_size'),
            $this->getConfig('watermark_image_position')
        );

        $this->mpdf->showWatermarkImage = $this->getConfig('show_watermark_image');
        $this->mpdf->showWatermarkText  = $this->getConfig('show_watermark');
        $this->mpdf->watermark_font     = $this->getConfig('watermark_font');
        $this->mpdf->watermarkTextAlpha = $this->getConfig('watermark_text_alpha');

        $this->mpdf->PDFA               = $this->getConfig('pdfa') ?: false;
        $this->mpdf->PDFAauto           = $this->getConfig('pdfaauto') ?: false;
        $this->mpdf->useActiveForms     = $this->getConfig('use_active_forms');
    }

    protected function getConfig(string $key, $default = null)
    {
        return $this->config[$key] ?? Config::get('pdf.' . $key, $default);
    }

    /**
     * @throws MpdfException
     */
    public function loadHTML(string $html): static
    {
        $this->mpdf->WriteHTML($html);
        return $this;
    }

    /**
     * @throws MpdfException
     */
    public function loadView(string $view, array $data = []): static
    {
        $html = View::make($view, $data)->render();
        return $this->loadHTML($html);
    }

    /**
     * @throws MpdfException
     */
    public function stream(string $filename = ''): ?string
    {
        return $this->mpdf->Output($filename ?: $this->filename, Destination::INLINE);
    }

    /**
     * @throws MpdfException
     */
    public function download(string $filename = ''): ?string
    {
        return $this->mpdf->Output($filename ?: $this->filename, Destination::DOWNLOAD);
    }

    /**
     * @throws MpdfException
     */
    public function save(string $filename = ''): ?string
    {
        return $this->mpdf->Output($filename ?: $this->filename, Destination::FILE);
    }

    /**
     * @throws MpdfException
     */
    public function output(): ?string
    {
        return response($this->mpdf->Output('', Destination::STRING_RETURN), Response::HTTP_OK, [
            'Content-Type' => 'application/pdf',
        ]);
    }
}
