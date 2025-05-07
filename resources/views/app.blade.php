<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title inertia>{{ config('app.name', 'Laravel') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

    @if (isset($meta))
    <!-- SEO -->
    <meta name="description" :content="{{ $meta['description'] }}" />

    <!-- Facebook Open Graph -->
    <meta property="og:site_name" :content="{{ $meta['og:site_name'] }}" />
    <meta property="og:title" :content="{{ $meta['og:title'] }}" />
    <meta property="og:description" :content="{{ $meta['og:description'] }}" />
    <meta property="og:image" :content="{{ $meta['og:image'] }}" />
    <meta property="og:type" :content="{{ $meta['og:type'] }}" />
    <meta property="og:url" :content="{{ $meta['og:url'] }}" />

    <!-- Twitter -->
    <meta name="twitter:card" :content="{{ $meta['twitter:card'] }}" />
    <meta name="twitter:title" :content="{{ $meta['twitter:title'] }}" />
    <meta name="twitter:description" :content="{{ $meta['twitter:description'] }}" />
    <meta name="twitter:image" :content="{{ $meta['twitter:image'] }}" />
    @endif

    @routes
    @vite(['resources/js/app.ts'])
    @inertiaHead
</head>

<body class="font-sans antialiased">
    @inertia
</body>

</html>
