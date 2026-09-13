<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'AZ Halal Marts · Cary, NC · Premium Halal Meats & Groceries')</title>

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="https://media.base44.com/images/public/6a2ccdd94a32a715825e5f58/2026c6a9d_Screenshot2026-06-13at52139AM.png">

    <!-- Preload Critical Above-the-Fold Resources -->
    <link rel="preload" href="/fonts/playfair-display-latin.woff2" as="font" type="font/woff2" crossorigin>
    <link rel="preload" href="/fonts/inter-latin.woff2" as="font" type="font/woff2" crossorigin>
    @stack('preload')

    <!-- Preconnect for External Media Assets -->
    <link rel="preconnect" href="https://images.unsplash.com" crossorigin>
    <link rel="dns-prefetch" href="https://images.unsplash.com">

    <!-- Bootstrap 5 CSS (Local Zero-Latency Load) -->
    <link rel="stylesheet" href="/css/bootstrap.min.css">

    <!-- Local Optimized Typography Fonts -->
    <link rel="stylesheet" href="/css/fonts.css">

    <!-- Custom Theme CSS (Critical theme styles) -->
    <link rel="stylesheet" href="/css/custom.css">

    <!-- Bootstrap Icons (Non-render-blocking async load) -->
    <link rel="stylesheet" href="/css/bootstrap-icons.min.css" media="print" onload="this.media='all'">
    <noscript><link rel="stylesheet" href="/css/bootstrap-icons.min.css"></noscript>

    <!-- AOS (Animate On Scroll) CSS (Non-render-blocking async load for below-the-fold animations) -->
    <link rel="stylesheet" href="/css/aos.css" media="print" onload="this.media='all'">
    <noscript><link rel="stylesheet" href="/css/aos.css"></noscript>

    @stack('styles')
</head>
<body class="d-flex flex-column min-vh-100">

    <!-- Navigation Header -->
    @include('partials.navbar')

    <!-- Main Content -->
    <main class="flex-grow-1">
        @yield('content')
    </main>

    <!-- Footer -->
    @include('partials.footer')

    <!-- Slide-out Cart Drawer -->
    @include('partials.cart-drawer')

    <!-- Floating WhatsApp Button -->
    @include('partials.whatsapp-widget')

    <!-- Custom Theme Alert & Confirmation Dialog -->
    @include('partials.alert-dialog')

    <!-- Bootstrap 5 Bundle JS (Local Deferred) -->
    <script defer src="/js/bootstrap.bundle.min.js"></script>

    <!-- AOS (Animate On Scroll) JS (Local Deferred) -->
    <script defer src="/js/aos.js"></script>
    <script>
        (function () {
            function initAOS() {
                if (typeof AOS !== 'undefined') {
                    AOS.init({
                        duration: 700,
                        easing: 'ease-out-cubic',
                        once: true,
                        offset: 50,
                        mirror: false,
                        debounceDelay: 50,
                        throttleDelay: 99
                    });
                } else {
                    // In case deferred script finishes after DOMContentLoaded
                    window.addEventListener('load', function () {
                        if (typeof AOS !== 'undefined') {
                            AOS.init({
                                duration: 700,
                                easing: 'ease-out-cubic',
                                once: true,
                                offset: 50,
                                mirror: false,
                                debounceDelay: 50,
                                throttleDelay: 99
                            });
                        }
                    });
                }
            }

            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', initAOS);
            } else {
                initAOS();
            }

            window.addEventListener('load', function () {
                if (typeof AOS !== 'undefined') {
                    AOS.refresh();
                }
            });
        })();
    </script>

    <!-- Custom Application JS (Deferred) -->
    <script defer src="/js/app.js?v={{ file_exists(public_path('js/app.js')) ? filemtime(public_path('js/app.js')) : time() }}"></script>
    @stack('scripts')
</body>
</html>
