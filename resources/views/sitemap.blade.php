{{--
    The sitemap. Rendered without a layout and without the theme, so it is the same
    document whatever the theme does; override it like any other view of a plugin.
--}}
<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
@foreach ($urls as $entry)
    <url>
        <loc>{{ $entry['url'] }}</loc>
@if ($entry['lastmod'] !== null)
        <lastmod>{{ $entry['lastmod']->format('Y-m-d') }}</lastmod>
@endif
    </url>
@endforeach
</urlset>
