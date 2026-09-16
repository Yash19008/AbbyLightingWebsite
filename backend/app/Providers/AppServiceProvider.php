<?php

namespace App\Providers;

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Handle proxy headers for Nginx reverse proxy
        if (request()->hasHeader('X-Forwarded-Host')) {
            $protocol = request()->header('X-Forwarded-Proto', 'http');
            $host = request()->header('X-Forwarded-Host');
            
            // Force root URL to use the proxied host (without port)
            URL::forceRootUrl($protocol . '://' . $host);
        }
        
        if(config('custom_config.FORCE_HTTPS')) { 
            URL::forceScheme('https');
        }
        Schema::defaultStringLength(191);
        view()->share(
            'catalogDownloadForm', url('catalog-download-user-form')
        );
        

    }
}
