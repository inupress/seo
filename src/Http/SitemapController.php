<?php

declare(strict_types=1);

namespace Inupress\Seo\Http;

use Illuminate\Http\Response;
use Illuminate\Support\Facades\Route;
use Inupress\Seo\Support\UrlSources;

/**
 * `/sitemap.xml`: the home page plus everything the installed plugins offer on the
 * `seo.urls` filter. A site with no plugins gets a sitemap with one URL, which is
 * correct for a site with one page.
 */
final class SitemapController
{
    public function __invoke(UrlSources $sources): Response
    {
        $urls = $sources->all();

        $home = Route::has('inupress.home') ? route('inupress.home') : url('/');

        // The home page comes first and only once, even when a plugin offers it too.
        array_unshift($urls, ['url' => $home, 'lastmod' => null]);

        $seen = [];
        $unique = [];

        foreach ($urls as $entry) {
            if (isset($seen[$entry['url']])) {
                continue;
            }

            $seen[$entry['url']] = true;
            $unique[] = $entry;
        }

        return response()
            ->view('inupress-seo::sitemap', ['urls' => $unique])
            ->header('Content-Type', 'application/xml; charset=UTF-8');
    }
}
