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
        
        // Share decorative categories with all views for mega dropdown
        view()->composer('*', function ($view) {
            try {
                $decorativeCategories = \App\Models\DecorativeCategory::where('status', 'active')
                    ->where('show_in_mega_dropdown', true)
                    ->orderBy('sort_order', 'ASC')
                    ->orderBy('name', 'ASC')
                    ->get()
                    ->map(function ($category) {
                        return [
                            'id' => $category->id,
                            'name' => $category->name,
                            'slug' => $category->slug,
                        ];
                    })
                    ->toArray();
                
                $view->with('decorativeCategories', $decorativeCategories);
            } catch (\Exception $e) {
                $view->with('decorativeCategories', []);
            }
        });
    }
}
