<nav class="site-navbar">
    <div class="container-xl d-flex align-items-center justify-content-between">
        
        <!-- Brand Logo -->
        <a class="navbar-brand" href="{{ route('home') }}">
            <span class="brand-title">AZ Halal</span>
            <span class="brand-sub">Marts · Cary, NC</span>
        </a>

        <!-- Desktop Navigation Links -->
        <div class="d-none d-md-flex align-items-center gap-4">
            <a class="nav-link-custom {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">Home</a>
            <a class="nav-link-custom {{ request()->routeIs('about') ? 'active' : '' }}" href="{{ route('about') }}">About</a>
            <a class="nav-link-custom {{ request()->routeIs('products') ? 'active' : '' }}" href="{{ route('products') }}">Products</a>
            <a class="nav-link-custom {{ request()->routeIs('catalog') ? 'active' : '' }}" href="{{ route('catalog') }}">Catalog</a>
            <a class="nav-link-custom {{ request()->routeIs('gallery') ? 'active' : '' }}" href="{{ route('gallery') }}">Gallery</a>
            <a class="nav-link-custom {{ request()->routeIs('contact') ? 'active' : '' }}" href="{{ route('contact') }}">Contact</a>
        </div>

        <!-- Action Items (Cart & Mobile Hamburger) -->
        <div class="d-flex align-items-center gap-2 gap-sm-3">
            <!-- Cart Trigger Button -->
            <button type="button" class="nav-cart-btn" data-bs-toggle="offcanvas" data-bs-target="#cartOffcanvas" aria-label="Open cart" title="View Cart">
                <i class="bi bi-bag" style="font-size: 1.25rem;"></i>
                <span class="cart-badge-count" style="display: none;">0</span>
            </button>

            <!-- Mobile Hamburger Toggle -->
            <button class="btn p-2 text-gold d-md-none border-0" type="button" data-bs-toggle="offcanvas" data-bs-target="#mobileNavOffcanvas" aria-controls="mobileNavOffcanvas" aria-label="Toggle navigation">
                <i class="bi bi-list fs-2"></i>
            </button>
        </div>

    </div>
</nav>

<!-- Mobile Navigation Offcanvas Drawer -->
<div class="offcanvas offcanvas-start mobile-nav-offcanvas" tabindex="-1" id="mobileNavOffcanvas" aria-labelledby="mobileNavOffcanvasLabel">
    <div class="offcanvas-header border-bottom border-secondary border-opacity-25 p-4">
        <div class="d-flex flex-column">
            <span class="brand-title">AZ Halal</span>
            <span class="brand-sub">Marts · Cary, NC</span>
        </div>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body p-4 d-flex flex-column justify-content-between">
        <div class="d-flex flex-column">
            <a class="mobile-nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">Home</a>
            <a class="mobile-nav-link {{ request()->routeIs('about') ? 'active' : '' }}" href="{{ route('about') }}">About Us</a>
            <a class="mobile-nav-link {{ request()->routeIs('products') ? 'active' : '' }}" href="{{ route('products') }}">Products & Pricing</a>
            <a class="mobile-nav-link {{ request()->routeIs('catalog') ? 'active' : '' }}" href="{{ route('catalog') }}">Catalog</a>
            <a class="mobile-nav-link {{ request()->routeIs('gallery') ? 'active' : '' }}" href="{{ route('gallery') }}">Gallery</a>
            <a class="mobile-nav-link {{ request()->routeIs('contact') ? 'active' : '' }}" href="{{ route('contact') }}">Contact</a>
        </div>

        <div class="pt-4 border-top border-secondary border-opacity-25">
            <a href="tel:9192448634" class="btn-gold-outline w-100 text-center mb-2">
                <span>📞 919-244-8634</span>
            </a>
        </div>
    </div>
</div>
