<?php

namespace Ashik\Pdf;

use Mpdf\MpdfException;

class PdfWrapper
{
    protected Pdf $pdf;


    public function __construct(Pdf $pdf)
    {
        $this->pdf = $pdf;
    }

    /**
     * @throws MpdfException
     */
    public function loadHTML(string $html): static
    {
        $this->pdf->loadHTML($html);
        return $this;
    }

    /**
     * @throws MpdfException
     */
    public function loadView(string $view, array $data = []): static
    {
        $this->pdf->loadView($view, $data);
        return $this;
    }

    /**
     * @throws MpdfException
     */
    public function save(string $filename = 'document.pdf'): ?string
    {
        return $this->pdf->save($filename);
    }


    /**
     * @throws MpdfException
     */
    public function download(string $filename = 'document.pdf'): ?string
    {
        return $this->pdf->download($filename);
    }

    /**
     * @throws MpdfException
     */
    public function output(): ?string
    {
        return $this->pdf->output('');
    }

    /**
     * @throws MpdfException
     */
    public function stream(string $filename = 'document.pdf'): ?string
    {
        return $this->pdf->stream($filename);
    }
}
