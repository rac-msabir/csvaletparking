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
        @vite(['resources/js/app.js', "resources/js/Pages/{$page['component']}.vue"])
        @inertiaHead
        <script>
            // Share the authenticated user's token with the Echo instance
            @auth
                window.authUser = @json([
                    'id' => auth()->id(),
                    'access_token' => auth()->user()->currentAccessToken()?->plainTextToken ?? '',
                ]);
            @else
                window.authUser = null;
            @endauth
        </script>
    </head>
    <body class="font-sans antialiased">
        @inertia
    </body>
</html>
