# inupress/seo

Search engine basics for the Inupress CMS: canonical and Open Graph tags, `sitemap.xml` and
`robots.txt`.

This package is developed in the `inupress/framework` monorepo (`packages/seo`). Report issues
and send changes there.

## Install

```bash
composer require inupress/seo
```

There is nothing to migrate and nothing to configure: the plugin has no tables and no admin
screens. Remove it with `composer remove inupress/seo` and the tags are gone — the theme does
not list it anywhere.

## What you get

- **Head tags:** canonical, `og:type`, `og:site_name`, `og:url`, `og:title`, `og:description`
  and the Twitter equivalents. The theme includes them softly, so a site without the plugin
  renders nothing extra:

```blade
{{-- the <head> of the default theme --}}
@includeIf('inupress-seo::head', ['title' => $title ?? null, 'description' => $description ?? null])
```

`<title>` and the plain meta description stay the theme's business; this view adds only what
the theme cannot know. `$title` and `$description` are optional — the home page passes neither
and still gets a correct canonical and a site-wide `og:title`.

- **`/sitemap.xml`** — the home page plus every URL a plugin offers.
- **`/robots.txt`** — `User-agent: *`, `Disallow: /admin`, `Sitemap:`. Override the view to
  change the rules.

Both documents are rendered without a layout, so they are the same whatever the theme does.
A theme may override them like any other view of the plugin.

A fresh Laravel ships `public/robots.txt` (`User-agent: *` / `Disallow:`), and a web server serves a
static file before Laravel ever runs: delete it, or the route never gets a chance. The starter has no
such file; `php artisan serve` and the tests agree.

## Hook

`seo.urls` (filter, owned by this plugin) is what a sitemap is built from. A plugin adds its
own URLs; `inupress/pages` does exactly that, so this package never depends on it:

```php
$hooks->addFilter('seo.urls', function (array $urls): array {
    foreach (Post::query()->published()->get() as $post) {
        $urls[] = ['url' => route('blog.show', $post), 'lastmod' => $post->updated_at];
    }

    return $urls;
});
```

An entry is a plain array: a non-empty string `url` and an optional `lastmod`
(a `DateTimeInterface`). Anything else is dropped, so one broken plugin cannot take the
sitemap down; the same URL twice is printed once.

## Not in this release

Per-URL overrides (a title, a description, an `og:image`, `noindex`), a settings screen, and a
sitemap index for sites with tens of thousands of URLs. There is no media library in the
project yet, so there is no `og:image`.

## Requirements

`inupress/core` 0.1, PHP 8.3 or newer. No dependency on Filament: the plugin has no panel
screens.

## License

MIT
