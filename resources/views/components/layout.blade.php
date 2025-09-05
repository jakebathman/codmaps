<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta
        name="viewport"
        content="width=device-width, initial-scale=1"
    >
    <meta
        name="theme-color"
        content="#cccccc"
        media="(prefers-color-scheme: light)"
    >

    <meta
        name="theme-color"
        content="#222222"
        media="(prefers-color-scheme: dark)"
    >

    <link
        rel="apple-touch-icon"
        sizes="180x180"
        href="/apple-touch-icon.png"
    >
    <link
        rel="icon"
        type="image/png"
        sizes="32x32"
        href="/favicon-32x32.png"
    >
    <link
        rel="icon"
        type="image/png"
        sizes="16x16"
        href="/favicon-16x16.png"
    >
    <link
        rel="manifest"
        href="/site.webmanifest"
    >

    <title>CoD Map Randomizer</title>

    @fluxAppearance
    @vite('resources/js/app.js')
    @vite('resources/css/app.css')


    @yield('header')
</head>

<body class="h-dvh w-full bg-white dark:bg-black">
    {{ $slot }}
</body>

@fluxScripts
@yield('scripts')

</html>
