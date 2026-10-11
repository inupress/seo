<?php

declare(strict_types=1);

namespace Inupress\Seo\Http;

use Illuminate\Http\Response;
use Illuminate\Support\Facades\Route;

/**
 * `/robots.txt`: crawl everything except the admin panel, and point at the sitemap.
 * A site that wants other rules overrides the view, the way a theme does.
 */
final class RobotsController
{
    public function __invoke(): Response
    {
        $sitemap = Route::has('seo.sitemap') ? route('seo.sitemap') : url('/sitemap.xml');

        return response()
            ->view('inupress-seo::robots', ['sitemap' => $sitemap])
            ->header('Content-Type', 'text/plain; charset=UTF-8');
    }
}
