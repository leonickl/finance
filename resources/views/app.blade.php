<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title inertia>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @routes
        @viteReactRefresh
        @vite(['resources/js/app.tsx', "resources/js/Pages/{$page['component']}.tsx"])
        @inertiaHead
    </head>
    <body class="font-sans antialiased">
        @if(env('APP_TYPE') === "dev")
            <!-- Red Flag in Page Corner -->
            <div class="fixed top-0 left-0 bg-red-600 text-white text-sm md:text-base font-bold px-10 py-3 rounded-br-lg z-50 shadow-lg">
                BETA
            </div>
        @endif

        @inertia
    </body>
</html>
