<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;
use App\Models\ProductMaster;
use App\Models\SubTag;
use App\Models\Category;
use App\Models\Project;
use App\Models\Event;
use App\Models\Collection;
use App\Models\Decorative\DecProduct;
use App\Models\Blog;
use App\Models\Catalogue;

class SitemapApiController extends Controller
{
    /**
     * Get all site URLs across both Laravel and Next.js for dynamic sitemap generation
     */
    public function index(): JsonResponse
    {
        $urls = Cache::remember('full_sitemap_urls_v1', 3600, function () {
            $list = [];
            $now = now()->toIso8601String();

            // 1. Core Static Pages (Both Next.js & Laravel)
            $staticRoutes = [
                ['path' => '/', 'priority' => 1.0, 'changefreq' => 'daily'],
                ['path' => '/collections', 'priority' => 0.9, 'changefreq' => 'weekly'],
                ['path' => '/decorative-lights', 'priority' => 0.9, 'changefreq' => 'weekly'],
                ['path' => '/products', 'priority' => 0.9, 'changefreq' => 'weekly'],
                ['path' => '/catalogues', 'priority' => 0.8, 'changefreq' => 'weekly'],
                ['path' => '/inspiration', 'priority' => 0.8, 'changefreq' => 'weekly'],
                ['path' => '/blogs', 'priority' => 0.8, 'changefreq' => 'daily'],
                ['path' => '/company', 'priority' => 0.8, 'changefreq' => 'monthly'],
                ['path' => '/contact', 'priority' => 0.8, 'changefreq' => 'monthly'],
                ['path' => '/projects', 'priority' => 0.8, 'changefreq' => 'weekly'],
                ['path' => '/clients', 'priority' => 0.7, 'changefreq' => 'monthly'],
                ['path' => '/abby-smart', 'priority' => 0.7, 'changefreq' => 'monthly'],
                ['path' => '/fair-events', 'priority' => 0.7, 'changefreq' => 'monthly'],
                ['path' => '/light-calculator', 'priority' => 0.7, 'changefreq' => 'monthly'],
                ['path' => '/career', 'priority' => 0.6, 'changefreq' => 'monthly'],
                ['path' => '/privacy-policy', 'priority' => 0.4, 'changefreq' => 'yearly'],
                ['path' => '/terms-and-conditions', 'priority' => 0.4, 'changefreq' => 'yearly'],
            ];

            foreach ($staticRoutes as $route) {
                $list[] = [
                    'path' => $route['path'],
                    'lastmod' => $now,
                    'priority' => $route['priority'],
                    'changefreq' => $route['changefreq'],
                    'type' => 'static',
                ];
            }

            // 2. Next.js Dynamic Collections
            try {
                $collections = Collection::where('is_active', true)->get(['slug', 'updated_at']);
                foreach ($collections as $col) {
                    if (!empty($col->slug)) {
                        $list[] = [
                            'path' => '/collections/' . $col->slug,
                            'lastmod' => optional($col->updated_at)->toIso8601String() ?: $now,
                            'priority' => 0.8,
                            'changefreq' => 'weekly',
                            'type' => 'collection',
                        ];
                    }
                }
            } catch (\Exception $e) {
                // Ignore query errors gracefully
            }

            // 3. Next.js Dynamic Decorative Products
            try {
                $decProducts = DecProduct::where('status', 'published')->get(['slug', 'updated_at']);
                foreach ($decProducts as $prod) {
                    if (!empty($prod->slug)) {
                        $list[] = [
                            'path' => '/decorative-lights/' . $prod->slug,
                            'lastmod' => optional($prod->updated_at)->toIso8601String() ?: $now,
                            'priority' => 0.8,
                            'changefreq' => 'weekly',
                            'type' => 'decorative_product',
                        ];
                    }
                }
            } catch (\Exception $e) {
            }

            // 4. Next.js Dynamic Blog Posts
            try {
                $blogs = Blog::where('status', 'published')->get(['slug', 'updated_at']);
                foreach ($blogs as $blog) {
                    if (!empty($blog->slug)) {
                        $list[] = [
                            'path' => '/blogs/' . $blog->slug,
                            'lastmod' => optional($blog->updated_at)->toIso8601String() ?: $now,
                            'priority' => 0.7,
                            'changefreq' => 'weekly',
                            'type' => 'blog',
                        ];
                    }
                }
            } catch (\Exception $e) {
            }

            // 5. Laravel Architectural Categories
            try {
                $categories = Category::where('is_active', 'yes')->get(['slug', 'updated_at']);
                foreach ($categories as $cat) {
                    if (!empty($cat->slug)) {
                        $list[] = [
                            'path' => '/category/' . urlencode($cat->slug),
                            'lastmod' => optional($cat->updated_at)->toIso8601String() ?: $now,
                            'priority' => 0.7,
                            'changefreq' => 'monthly',
                            'type' => 'category',
                        ];
                    }
                }
            } catch (\Exception $e) {
            }

            // 6. Laravel Architectural SubTags (Product Families)
            try {
                $subTags = SubTag::where('is_active', 'yes')->get(['slug', 'updated_at']);
                foreach ($subTags as $sub) {
                    if (!empty($sub->slug)) {
                        $list[] = [
                            'path' => '/products/' . urlencode($sub->slug),
                            'lastmod' => optional($sub->updated_at)->toIso8601String() ?: $now,
                            'priority' => 0.7,
                            'changefreq' => 'monthly',
                            'type' => 'product_family',
                        ];
                    }
                }
            } catch (\Exception $e) {
            }

            // 7. Laravel Architectural Products
            try {
                $products = ProductMaster::where('is_active', 'yes')->get(['slug', 'updated_at']);
                foreach ($products as $prod) {
                    if (!empty($prod->slug)) {
                        $list[] = [
                            'path' => '/product/' . urlencode($prod->slug),
                            'lastmod' => optional($prod->updated_at)->toIso8601String() ?: $now,
                            'priority' => 0.6,
                            'changefreq' => 'monthly',
                            'type' => 'product',
                        ];
                    }
                }
            } catch (\Exception $e) {
            }

            // 8. Laravel Projects
            try {
                $projects = Project::where('is_active', 'yes')->get(['slug', 'updated_at']);
                foreach ($projects as $proj) {
                    if (!empty($proj->slug)) {
                        $list[] = [
                            'path' => '/projects/' . urlencode($proj->slug),
                            'lastmod' => optional($proj->updated_at)->toIso8601String() ?: $now,
                            'priority' => 0.7,
                            'changefreq' => 'monthly',
                            'type' => 'project',
                        ];
                    }
                }
            } catch (\Exception $e) {
            }

            // 9. Laravel Events
            try {
                $events = Event::where('is_active', 'yes')->get(['slug', 'updated_at']);
                foreach ($events as $event) {
                    if (!empty($event->slug)) {
                        $list[] = [
                            'path' => '/event-detail/' . urlencode($event->slug),
                            'lastmod' => optional($event->updated_at)->toIso8601String() ?: $now,
                            'priority' => 0.6,
                            'changefreq' => 'monthly',
                            'type' => 'event',
                        ];
                    }
                }
            } catch (\Exception $e) {
            }

            return $list;
        });

        return response()->json([
            'success' => true,
            'total' => count($urls),
            'data' => $urls,
        ]);
    }
}

