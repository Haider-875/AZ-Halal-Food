<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'AZ Halal Marts · Cary, NC · Premium Halal Meats & Groceries')</title>

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="https://media.base44.com/images/public/6a2ccdd94a32a715825e5f58/2026c6a9d_Screenshot2026-06-13at52139AM.png">

    <!-- DNS Prefetch & Preconnect for External Assets -->
    <link rel="dns-prefetch" href="https://fonts.googleapis.com">
    <link rel="dns-prefetch" href="https://fonts.gstatic.com">
    <link rel="dns-prefetch" href="https://cdn.jsdelivr.net">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <!-- Google Fonts with display swap -->
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,600;0,700;1,400&family=Inter:wght@300;400;500;600;700&family=Playfair+Display:ital,wght@0,400;0,700;0,900;1,400&display=swap" rel="stylesheet">

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- AOS (Animate On Scroll) CSS via fast jsDelivr CDN -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css">

    <!-- Custom Theme CSS -->
    <link rel="stylesheet" href="/css/custom.css">
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

    <!-- Bootstrap 5 Bundle JS (Deferred for non-blocking rendering) -->
    <script defer src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- AOS (Animate On Scroll) JS via fast jsDelivr CDN -->
    <script defer src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
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
