<?php

namespace App\Providers;

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Cache;
use App\Models\Tag;
use App\Models\Decorative\DecCategory;
use App\Models\Collection;
use App\Models\MenuItem;
use Illuminate\Pagination\Paginator;

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
        // Use Bootstrap 4 for pagination to match admin panel UI
        Paginator::useBootstrapFour();

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

        // Share SSR categories, collections, and worlds/smart lighting with header views
        View::composer('partials.common.header-nextjs', function ($view) {
            $archCategories = Cache::remember('header_ssr_arch_categories', 3600, function () {
                try {
                    return Tag::where('is_active', 'yes')
                        ->where('is_featured', 1)
                        ->orderBy('id', 'ASC')
                        ->get(['id', 'name', 'display_name', 'slug'])
                        ->map(function ($tag) {
                            $slug = $tag->slug ?? '';
                            $title = $tag->display_name ?: $tag->name;
                            return [
                                'id' => $tag->id,
                                'title' => $title,
                                'name' => $title,
                                'slug' => $slug,
                                'url' => '/products/category/' . $slug,
                            ];
                        })->toArray();
                } catch (\Throwable $e) {
                    return [];
                }
            });

            $decCategories = Cache::remember('header_ssr_dec_categories', 3600, function () {
                try {
                    return DecCategory::where('is_featured', 1)
                        ->orderBy('name', 'asc')
                        ->get(['id', 'name', 'slug'])
                        ->toArray();
                } catch (\Throwable $e) {
                    return [];
                }
            });

            $collections = Cache::remember('header_ssr_collections', 3600, function () {
                try {
                    return Collection::where('is_active', true)
                        ->ordered()
                        ->get(['id', 'name', 'slug'])
                        ->toArray();
                } catch (\Throwable $e) {
                    return [];
                }
            });

            $headerMegaItems = Cache::remember('header_ssr_mega_items', 3600, function () {
                try {
                    return MenuItem::where('location', 'header_mega')
                        ->active()
                        ->ordered()
                        ->get()
                        ->toArray();
                } catch (\Throwable $e) {
                    return [];
                }
            });

            $view->with([
                'archCategories' => $archCategories,
                'decCategories' => $decCategories,
                'collections' => $collections,
                'headerMegaItems' => $headerMegaItems,
            ]);
        });

        // Share SSR footer menu items with footer views
        View::composer('partials.common.footer-nextjs', function ($view) {
            $footerCol1 = Cache::remember('footer_ssr_col_1', 3600, function () {
                try {
                    return MenuItem::with('children')
                        ->where('location', 'footer_col_1')
                        ->whereNull('parent_id')
                        ->active()
                        ->ordered()
                        ->get()
                        ->toArray();
                } catch (\Throwable $e) {
                    return [];
                }
            });

            $footerCol2 = Cache::remember('footer_ssr_col_2', 3600, function () {
                try {
                    return MenuItem::where('location', 'footer_col_2')
                        ->active()
                        ->ordered()
                        ->get()
                        ->toArray();
                } catch (\Throwable $e) {
                    return [];
                }
            });

            $footerCol3 = Cache::remember('footer_ssr_col_3', 3600, function () {
                try {
                    return MenuItem::where('location', 'footer_col_3')
                        ->active()
                        ->ordered()
                        ->get()
                        ->toArray();
                } catch (\Throwable $e) {
                    return [];
                }
            });

            $view->with([
                'footerCol1' => $footerCol1,
                'footerCol2' => $footerCol2,
                'footerCol3' => $footerCol3,
            ]);
        });
    }
}


