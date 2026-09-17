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
use App\Http\Controllers\Api\DecorativeProductApiController;
use App\Http\Controllers\Api\BlogCategoryApiController;
use App\Http\Controllers\Api\BlogApiController;
use App\Http\Controllers\Api\WatchAndShopApiController;
use App\Http\Controllers\Api\CompositionApiController;
use App\Http\Controllers\Api\CatalogueApiController;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Categories (Architectural)
Route::get('/categories', [CategoryApiController::class, 'index']);

// Clients
Route::controller(ClientApiController::class)->group(function () {
    Route::get('/clients', 'index');
    Route::get('/clients/{id}', 'show');
});

// Sliders
Route::controller(HomeSliderApiController::class)->group(function () {
    Route::get('/sliders', 'index');
    Route::get('/sliders/{id}', 'show');
});

// Projects
Route::controller(ProjectApiController::class)->group(function () {
    Route::get('/projects', 'index');
    Route::get('/projects/slug/{slug}', 'showBySlug');
    Route::get('/projects/{id}', 'show');
});

// Events
Route::controller(EventApiController::class)->group(function () {
    Route::get('/events', 'index');
    Route::get('/events/{id}', 'show');
});

// General Sections
Route::get('/manufacturing-section', [ManufacturingSectionApiController::class, 'index']);
Route::get('/news-items', [NewsItemApiController::class, 'index']);
Route::get('/products/new-arrivals', [NewArrivalsApiController::class, 'index']);
Route::get('/light-worlds', [LightWorldApiController::class, 'index']);

// Decorative Products & Categories
Route::controller(DecorativeProductApiController::class)->group(function () {
    Route::get('/dec-categories', 'categories');
    Route::get('/dec-collections', 'collections');
    Route::get('/dec-products', 'index');
    Route::get('/dec-products/{slug}', 'show');
});

// Collections
Route::controller(CollectionApiController::class)->group(function () {
    Route::get('/collections', 'index');
    Route::get('/collections/{slug}', 'show');
});

// Color Masters
Route::controller(ColorMasterApiController::class)->group(function () {
    Route::get('/color-masters', 'index');
    Route::get('/colors', 'index');
    Route::get('/colors/by-category', 'byCategory');
    Route::get('/colors/categories', 'categories');
    Route::get('/colors/{code}', 'show');
});

// Blogs & Blog Categories
Route::controller(BlogCategoryApiController::class)->group(function () {
    Route::get('/blog-categories', 'index');
    Route::get('/blog-categories/{slug}', 'show');
});

Route::controller(BlogApiController::class)->group(function () {
    Route::get('/blogs', 'index');
    Route::get('/blogs/{slug}', 'show');
});

// Watch & Shop
Route::get('/watch-and-shops', [WatchAndShopApiController::class, 'index']);

// Compositions
Route::get('/compositions/showcase', [CompositionApiController::class, 'showcase']);

// Catalogues
Route::controller(CatalogueApiController::class)->group(function () {
    Route::get('/catalogue-categories', 'categories');
    Route::get('/catalogues', 'index');
    Route::get('/catalogues/{slug}', 'show');
    Route::get('/catalogues/{id}/download-pdf', 'downloadPdf');
    Route::post('/catalog-downloads', 'storeDownloadLead');
});
