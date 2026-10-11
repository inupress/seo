{{--
    The head tags of inupress/seo: canonical, Open Graph and Twitter. A theme includes it
    softly, so the site keeps working without the plugin and a theme never lists plugins:

        @includeIf('inupress-seo::head', ['title' => $title ?? null, 'description' => $description ?? null])

    <title> and the plain meta description stay the theme's business; this file adds only
    what the theme cannot know. $title and $description are optional: a page that passes
    neither still gets a correct canonical and a site-wide og:title.
--}}
@php
    $title = $title ?? null;
    $description = $description ?? null;
    $site = config('app.name');
    $canonical = url()->current();
    $headline = filled($title) ? $title : $site;
@endphp
<link rel="canonical" href="{{ $canonical }}">

<meta property="og:type" content="website">
<meta property="og:site_name" content="{{ $site }}">
<meta property="og:url" content="{{ $canonical }}">
<meta property="og:title" content="{{ $headline }}">
@if (filled($description))
    <meta property="og:description" content="{{ $description }}">
@endif

<meta name="twitter:card" content="summary">
<meta name="twitter:title" content="{{ $headline }}">
@if (filled($description))
    <meta name="twitter:description" content="{{ $description }}">
@endif
