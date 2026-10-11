<?php

declare(strict_types=1);

namespace Inupress\Seo\Support;

use DateTimeInterface;
use Inupress\Contracts\HookRegistry;

/**
 * The URLs a sitemap is built from. A plugin offers its own URLs on the filter this plugin
 * owns, so `inupress/seo` never depends on `inupress/pages` and a plugin nobody installed
 * simply contributes nothing — the same shape as `menus.link_sources` (docs/decisions/0011):
 *
 * ```php
 * $hooks->addFilter('seo.urls', function (array $urls): array {
 *     foreach (Post::query()->published()->get() as $post) {
 *         $urls[] = ['url' => route('blog.show', $post), 'lastmod' => $post->updated_at];
 *     }
 *
 *     return $urls;
 * });
 * ```
 *
 * An entry is a plain array: a non-empty string `url` and an optional `lastmod`
 * (`DateTimeInterface`). Anything else is dropped, so one broken plugin cannot take the
 * sitemap down. The same URL twice is printed once.
 */
final class UrlSources
{
    /** Filter name, owned by this plugin: docs/hooks.md asks for the plugin prefix. */
    public const FILTER = 'seo.urls';

    public function __construct(private readonly HookRegistry $hooks) {}

    /**
     * Every registered URL, in registration order and without duplicates.
     *
     * @return list<array{url: string, lastmod: DateTimeInterface|null}>
     */
    public function all(): array
    {
        return self::sanitize($this->hooks->applyFilters(self::FILTER, []));
    }

    /**
     * @return list<array{url: string, lastmod: DateTimeInterface|null}>
     */
    private static function sanitize(mixed $raw): array
    {
        if (! is_array($raw)) {
            return [];
        }

        $urls = [];
        $seen = [];

        foreach ($raw as $entry) {
            if (! is_array($entry)) {
                continue;
            }

            $url = $entry['url'] ?? null;

            if (! is_string($url) || $url === '') {
                continue;
            }

            if (isset($seen[$url])) {
                continue;
            }

            $lastmod = $entry['lastmod'] ?? null;

            if (! $lastmod instanceof DateTimeInterface) {
                $lastmod = null;
            }

            $seen[$url] = true;
            $urls[] = ['url' => $url, 'lastmod' => $lastmod];
        }

        return $urls;
    }
}
