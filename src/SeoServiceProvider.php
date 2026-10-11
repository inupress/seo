<?php

declare(strict_types=1);

namespace Inupress\Seo;

use Inupress\Seo\Support\UrlSources;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

/**
 * Search engine basics: the head tags a theme includes, plus `sitemap.xml` and `robots.txt`.
 *
 * No admin screens and no tables: the plugin reads what the site already has. There is
 * nothing to migrate, so nothing is registered for migrations either.
 */
final class SeoServiceProvider extends PackageServiceProvider
{
    public static string $name = 'inupress-seo';

    public function packageRegistered(): void
    {
        // Shared so the filter is applied once per request, not once per URL.
        $this->app->singleton(UrlSources::class);
    }

    public function configurePackage(Package $package): void
    {
        $package
            ->name(self::$name)
            ->hasViews()
            ->hasRoute('web');
    }
}
