<?php

namespace Ashik\Pdf\Facades;

use Illuminate\Support\Facades\Facade;

class Pdf extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'pdf.wrapper';
    }
}
