<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title inertia>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

        <!-- Scripts -->
        @routes
        @viteReactRefresh
        @vite(['resources/js/app.jsx', "resources/js/Pages/{$page['component']}.jsx"])
        @inertiaHead
    </head>
    <body class="font-sans antialiased">
        @inertia
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
        <script>
            window.addEventListener('load', function() {
                if (window.innerWidth >= 992) {
                    document.querySelectorAll('.navbar-collapse').forEach(function(nav) {
                        nav.classList.add('show');
                    });
                }
                
                var collapseElements = [].slice.call(document.querySelectorAll('.collapse:not(.navbar-collapse)'));
                collapseElements.forEach(function (collapseEl) {
                    new bootstrap.Collapse(collapseEl, {
                        toggle: false
                    });
                });
            });
        </script>
    </body>
</html>
