<?php

namespace Ashik\Pdf;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\View;

class PdfWrapper
{

    public function loadHTML($html, $config = []): pdf
    {
        return new pdf($html, $config);
    }


    public function loadFile($file, $config = []): pdf
    {
        return new pdf(File::get($file), $config);
    }


    public function loadView($view, $data = [], $mergeData = [], $config = []): pdf
    {
        return new pdf(View::make($view, $data, $mergeData)->render(), $config);
    }
}
