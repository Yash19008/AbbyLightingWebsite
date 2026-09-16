<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ClientApiController;
use App\Http\Controllers\Api\HomeSliderApiController;
use App\Http\Controllers\Api\ProjectApiController;
use App\Http\Controllers\Api\EventApiController;

use App\Http\Controllers\Api\ManufacturingSectionApiController;
use App\Http\Controllers\Api\NewsItemApiController;
use App\Http\Controllers\Api\NewArrivalsApiController;
use App\Http\Controllers\Api\LightWorldApiController;
use App\Http\Controllers\Api\CollectionApiController;
use App\Http\Controllers\Api\ColorMasterApiController;
use App\Http\Controllers\Api\CategoryApiController;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/categories', [CategoryApiController::class, 'index']);

Route::get('/clients', [ClientApiController::class, 'index']);
Route::get('/clients/{id}', [ClientApiController::class, 'show']);

Route::get('/sliders', [HomeSliderApiController::class, 'index']);
Route::get('/sliders/{id}', [HomeSliderApiController::class, 'show']);

Route::get('/projects', [ProjectApiController::class, 'index']);
Route::get('/projects/slug/{slug}', [ProjectApiController::class, 'showBySlug']);
Route::get('/projects/{id}', [ProjectApiController::class, 'show']);

Route::get('/events', [EventApiController::class, 'index']);
Route::get('/events/{id}', [EventApiController::class, 'show']);



Route::get('/manufacturing-section', [ManufacturingSectionApiController::class, 'index']);

Route::get('/news-items', [NewsItemApiController::class, 'index']);

Route::get('/products/new-arrivals', [NewArrivalsApiController::class, 'index']);

Route::get('/light-worlds', [LightWorldApiController::class, 'index']);

// Collections — used by Next.js collection pages
Route::get('/collections', [CollectionApiController::class, 'index']);
Route::get('/collections/{slug}', [CollectionApiController::class, 'show']);

// Color Masters — for tones section
Route::get('/color-masters', [ColorMasterApiController::class, 'index']);
Route::get('/collections', [CollectionApiController::class, 'index']);
Route::get('/collections/{slug}', [CollectionApiController::class, 'show']);

// Color Masters — used for displaying colors across the application
Route::get('/colors', [ColorMasterApiController::class, 'index']);
Route::get('/colors/by-category', [ColorMasterApiController::class, 'byCategory']);
Route::get('/colors/categories', [ColorMasterApiController::class, 'categories']);
Route::get('/colors/{code}', [ColorMasterApiController::class, 'show']);

// Blog Categories API
Route::get('/blog-categories', [\App\Http\Controllers\Api\BlogCategoryApiController::class, 'index']);
Route::get('/blog-categories/{slug}', [\App\Http\Controllers\Api\BlogCategoryApiController::class, 'show']);

// Blogs API
Route::get('/blogs', [\App\Http\Controllers\Api\BlogApiController::class, 'index']);
Route::get('/blogs/{slug}', [\App\Http\Controllers\Api\BlogApiController::class, 'show']);

// Watch & Shop (Reels / Videos) API
Route::get('/watch-and-shops', [\App\Http\Controllers\Api\WatchAndShopApiController::class, 'index']);

// Compositions
Route::get('/compositions/showcase', [\App\Http\Controllers\Api\CompositionApiController::class, 'showcase']);

// Catalogues API
Route::get('/catalogue-categories', [\App\Http\Controllers\Api\CatalogueApiController::class, 'categories']);
Route::get('/catalogues', [\App\Http\Controllers\Api\CatalogueApiController::class, 'index']);
Route::get('/catalogues/{slug}', [\App\Http\Controllers\Api\CatalogueApiController::class, 'show']);
Route::get('/catalogues/{id}/download-pdf', [\App\Http\Controllers\Api\CatalogueApiController::class, 'downloadPdf']);
Route::post('/catalog-downloads', [\App\Http\Controllers\Api\CatalogueApiController::class, 'storeDownloadLead']);




