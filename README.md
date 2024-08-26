# Laravel PDF: mPDF wrapper for Laravel 7.0

> Easily generate PDF documents from HTML right inside of Laravel using this mPDF wrapper.


## Installation

Require this package in your `composer.json` or install it by running:

```
composer require ashik/pdf
```

> Note: This package supports auto-discovery features of Laravel 5.5+, You only need to manually add the service provider and alias if working on Laravel version lower then 7.0

To start using Laravel, add the Service Provider and the Facade to your `config/app.php`:

```php
'providers' => [
	// ...
	Ashik\Pdf\PdfServiceProvider::class
]
```

```php
'aliases' => [
	// ...
	'PDF' => Ashik\Pdf\Facades\Mpdf::class
]
```

Now, you should publish package's config file to your config directory by using following command:

```
php artisan vendor:publish --tag=pdf-config
```

## Basic Usage

To use Laravel PDF add something like this to one of your controllers. You can pass data to a view in `/resources/views`.

```php
use Ashik\Pdf\Facades\Pdf;

class ReportController extends Controller 
{
    public function viewPdf()
    {
        $pdf = PDF::loadView('pdf.document', [
            'foo' => 'bar'
        ]);
        return $pdf->stream('document.pdf');
    }
}
```

## Config

You can use a custom file to overwrite the default configuration. Just execute `php artisan vendor:publish --tag=pdf-config` or create `config/pdf.php` and add this:

```php
return [
    'mode'                       => '',
    'format'                     => 'A4',
    'default_font_size'          => '12',
    'default_font'               => 'sans-serif',
    'margin_left'                => 10,
    'margin_right'               => 10,
    'margin_top'                 => 10,
    'margin_bottom'              => 10,
    'margin_header'              => 0,
    'margin_footer'              => 0,
    'orientation'                => 'P',
    'title'                      => 'Laravel mPDF',
    'author'                     => '',
    'watermark'                  => '',
    'show_watermark'             => false,
    'show_watermark_image'       => false,
    'watermark_font'             => 'sans-serif',
    'display_mode'               => 'fullpage',
    'watermark_text_alpha'       => 0.1,
    'watermark_image_path'       => '',
    'watermark_image_alpha'      => 0.2,
    'watermark_image_size'       => 'D',
    'watermark_image_position'   => 'P',
    'custom_font_dir'            => '',
    'custom_font_data'           => [],
    'auto_language_detection'    => false,
    'temp_dir'                   => storage_path('app'),
    'pdfa'                       => false,
    'pdfaauto'                   => false,
    'use_active_forms'           => false,
];
```

To override this configuration on a per-file basis use the fourth parameter of the initializing call like this:

```php
// ...

PDF::loadView('pdf', $data, [], [
    'title' => 'Another Title',
])->save($pdfFilePath);
```

## Included Fonts

By default you can use all the fonts [shipped with Mpdf](https://mpdf.github.io/fonts-languages/available-fonts-v6.html).

## Custom Fonts

You can use your own fonts in the generated PDFs. The TTF files have to be located in one folder, e.g. `resources/fonts/`. Add this to your configuration file (`/config/pdf.php`):

```php
return [
    'custom_font_dir'  => base_path('resources/fonts/'), // don't forget the trailing slash!
    'custom_font_data' => [
        'examplefont' => [ // must be lowercase and snake_case
            'R'  => 'ExampleFont-Regular.ttf',    // regular font
            'B'  => 'ExampleFont-Bold.ttf',       // optional: bold font
            'I'  => 'ExampleFont-Italic.ttf',     // optional: italic font
            'BI' => 'ExampleFont-Bold-Italic.ttf' // optional: bold-italic font
        ]
      // ...add as many as you want.
    ]
];
```

Now you can use the font in CSS:

```css
body {
  font-family: 'examplefont', sans-serif;
}
```

## Chunk HTML

For big HTML you might get `Uncaught Mpdf\MpdfException: The HTML code size is larger than pcre.backtrack_limit xxx` error, or you might just get [empty or blank result](https://mpdf.github.io/troubleshooting/known-issues.html#blank-pages-or-some-sections-missing). In these situations you can use chunk methods while you added a separator to your HTML:

```php
//....
use Ashik\Pdf\Facades\Pdf;
class ReportController extends Controller 
{
    public function generate_pdf()
    {
        $data = [
            'foo' => 'hello 1',
            'bar' => 'hello 2'
        ];
        $pdf = PDF::chunkLoadView('<html-separator/>', 'pdf.document', $data);
        return $pdf->stream('document.pdf');
    }
}
```
```html
<div>
    <h1>Hello World</h1>

    <table>
        <tr><td>{{ $foo }}</td></tr>
    </table>
    
    <html-separator/>

    <table>
        <tr><td>{{ $bar }}</td></tr>
    </table>

    <html-separator/>
</div>
```

## License

Laravel Mpdf is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT)
