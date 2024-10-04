<?php

namespace Ashik\Pdf;

use Illuminate\Support\ServiceProvider;

class PdfServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected bool $defer = false;

    /**
     * Register services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../config/pdf.php', 'pdf'
        );

        $this->app->singleton('mpdf.wrapper', function ($app) {
            return new PdfWrapper();
        });
    }


    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot(): void
    {
        $this->publishes([
            __DIR__.'/../config/pdf.php' => config_path('pdf.php')
        ], 'pdf-config');
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides(): array
    {
        return [
            'mpdf'
        ];
    }
}
