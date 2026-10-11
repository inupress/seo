<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Inupress\Seo\Http\RobotsController;
use Inupress\Seo\Http\SitemapController;

// Package routes have no middleware group by default. "web" keeps cookies, sessions and
// URL generation the same as the rest of the site; controllers, never closures, so
// `route:cache` works.
Route::middleware('web')->group(function (): void {
    Route::get('/sitemap.xml', SitemapController::class)->name('seo.sitemap');
    Route::get('/robots.txt', RobotsController::class)->name('seo.robots');
});
