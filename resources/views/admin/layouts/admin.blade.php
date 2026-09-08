<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Portal · AZ Halal Marts')</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,600;0,700;1,400&family=Inter:wght@300;400;500;600;700&family=Playfair+Display:ital,wght@0,400;0,700;0,900;1,400&display=swap" rel="stylesheet">

    <!-- Admin CSS -->
    <style>
        :root {
            --admin-bg: #070E07;
            --admin-sidebar: #0A140A;
            --admin-card: #0F1F0F;
            --admin-card-hover: #152B15;
            --gold: #D4AF37;
            --gold-light: #E8C84A;
            --parchment: #F5F0E8;
            --parchment-dim: rgba(245, 240, 232, 0.7);
            --parchment-muted: rgba(245, 240, 232, 0.45);
            --border-gold: rgba(212, 175, 55, 0.2);
            --font-heading: 'Playfair Display', Georgia, serif;
            --font-body: 'Inter', system-ui, sans-serif;
        }

        body {
            background-color: var(--admin-bg);
            color: var(--parchment);
            font-family: var(--font-body);
            font-weight: 300;
            margin: 0;
            overflow-x: hidden;
        }

        .font-heading { font-family: var(--font-heading); }
        .text-gold { color: var(--gold) !important; }
        .bg-gold { background-color: var(--gold) !important; color: var(--admin-bg) !important; }

        /* Sidebar */
        .admin-sidebar {
            width: 260px;
            background-color: var(--admin-sidebar);
            border-right: 1px solid var(--border-gold);
            min-height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            bottom: 0;
            z-index: 1000;
            transition: all 0.3s ease;
        }

        .admin-main {
            margin-left: 260px;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        @media (max-width: 991px) {
            .admin-sidebar {
                transform: translateX(-100%);
            }
            .admin-sidebar.show {
                transform: translateX(0);
            }
            .admin-main {
                margin-left: 0;
            }
        }

        .admin-nav-link {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.75rem 1.25rem;
            color: var(--parchment-dim);
            text-decoration: none;
            font-size: 13px;
            letter-spacing: 0.05em;
            font-weight: 400;
            border-left: 3px solid transparent;
            transition: all 0.2s ease;
        }

        .admin-nav-link:hover, .admin-nav-link.active {
            background: rgba(212, 175, 55, 0.08);
            color: var(--gold);
            border-left-color: var(--gold);
        }

        .admin-nav-link i {
            font-size: 1.1rem;
        }

        /* Cards & Tables */
        .admin-card {
            background-color: var(--admin-card);
            border: 1px solid var(--border-gold);
            border-radius: 6px;
            overflow: hidden;
        }

        .admin-table {
            color: var(--parchment);
            margin-bottom: 0;
        }

        .admin-table th {
            background-color: rgba(13, 23, 13, 0.8) !important;
            color: var(--gold) !important;
            font-family: var(--font-heading);
            font-size: 12px;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            border-bottom: 1px solid var(--border-gold) !important;
            padding: 0.85rem 1rem;
        }

        .admin-table td {
            background-color: transparent !important;
            color: var(--parchment) !important;
            border-bottom: 1px solid rgba(212, 175, 55, 0.1) !important;
            padding: 0.85rem 1rem;
            font-size: 13px;
            vertical-align: middle;
        }

        .admin-table tbody tr:hover td {
            background-color: rgba(212, 175, 55, 0.04) !important;
        }

        .form-control, .form-select {
            background-color: rgba(13, 23, 13, 0.8) !important;
            border: 1px solid var(--border-gold) !important;
            color: var(--parchment) !important;
            border-radius: 4px;
            padding: 0.65rem 1rem;
            font-size: 13px;
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--gold) !important;
            box-shadow: 0 0 10px rgba(212, 175, 55, 0.25) !important;
        }

        .btn-gold {
            background-color: var(--gold);
            color: #070E07;
            font-weight: 600;
            font-size: 12px;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            border: none;
            padding: 0.6rem 1.2rem;
            border-radius: 4px;
            transition: all 0.2s ease;
        }

        .btn-gold:hover {
            background-color: var(--gold-light);
            color: #000;
            box-shadow: 0 0 15px rgba(212, 175, 55, 0.4);
        }

        .btn-outline-gold {
            background-color: transparent;
            color: var(--gold);
            border: 1px solid var(--gold);
            font-weight: 500;
            font-size: 12px;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            padding: 0.55rem 1.1rem;
            border-radius: 4px;
            transition: all 0.2s ease;
        }

        .btn-outline-gold:hover {
            background-color: var(--gold);
            color: #070E07;
        }

        /* Admin Pagination */
        .pagination {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            margin: 0;
            padding: 0;
            list-style: none;
        }

        .pagination .page-item {
            display: inline-flex;
        }

        .pagination .page-link {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 36px;
            height: 36px;
            padding: 0 0.75rem;
            font-size: 12px;
            font-weight: 500;
            text-decoration: none;
            background-color: var(--admin-card) !important;
            color: var(--parchment) !important;
            border: 1px solid var(--border-gold) !important;
            border-radius: 4px !important;
            transition: all 0.2s ease;
        }

        .pagination .page-item:not(.active):not(.disabled) .page-link:hover {
            background-color: rgba(212, 175, 55, 0.15) !important;
            color: var(--gold) !important;
            border-color: var(--gold) !important;
        }

        .pagination .page-item.active .page-link {
            background-color: var(--gold) !important;
            color: var(--admin-bg) !important;
            border-color: var(--gold) !important;
            font-weight: 700 !important;
        }

        .pagination .page-item.disabled .page-link {
            background-color: rgba(10, 19, 10, 0.4) !important;
            color: var(--parchment-muted) !important;
            border-color: rgba(212, 175, 55, 0.1) !important;
            opacity: 0.5;
            cursor: not-allowed;
        }
    </style>
</head>
<body>

    <!-- Sidebar -->
    <aside class="admin-sidebar d-flex flex-column justify-content-between p-3" id="adminSidebar">
        <div>
            <!-- Admin Brand -->
            <div class="d-flex align-items-center justify-content-between pb-3 mb-3 border-bottom border-secondary border-opacity-25">
                <a href="{{ route('admin.dashboard') }}" class="text-decoration-none">
                    <span class="font-heading font-black text-gold fs-5 text-uppercase fw-bold" style="letter-spacing: 0.15em;">AZ Halal</span>
                    <span class="d-block text-parchment-muted text-uppercase" style="font-size: 8px; letter-spacing: 0.3em;">Admin Portal</span>
                </a>
                <button class="btn btn-link text-parchment d-lg-none p-0" id="sidebarCloseBtn"><i class="bi bi-x fs-3"></i></button>
            </div>

            <!-- Navigation Links -->
            <nav class="d-flex flex-column gap-1">
                <a href="{{ route('admin.dashboard') }}" class="admin-nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <i class="bi bi-speedometer2"></i> Dashboard
                </a>
                <a href="{{ route('admin.orders.index') }}" class="admin-nav-link {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">
                    <i class="bi bi-bag-check"></i> Orders
                    @php $pendingCount = \App\Models\Order::where('status', 'pending')->count(); @endphp
                    @if($pendingCount > 0)
                        <span class="badge bg-warning text-dark ms-auto" style="font-size: 10px;">{{ $pendingCount }}</span>
                    @endif
                </a>
                <a href="{{ route('admin.products.index') }}" class="admin-nav-link {{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
                    <i class="bi bi-egg-fried"></i> Foods / Products
                </a>
                <a href="{{ route('admin.categories.index') }}" class="admin-nav-link {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                    <i class="bi bi-tags"></i> Categories & Subs
                </a>
                <a href="{{ route('admin.gallery.index') }}" class="admin-nav-link {{ request()->routeIs('admin.gallery.*') ? 'active' : '' }}">
                    <i class="bi bi-images"></i> Gallery Archive
                </a>
                <a href="{{ route('admin.sections.index') }}" class="admin-nav-link {{ request()->routeIs('admin.sections.*') ? 'active' : '' }}">
                    <i class="bi bi-layout-text-window-reverse"></i> Dynamic Sections
                </a>
                <a href="{{ route('admin.inquiries.index') }}" class="admin-nav-link {{ request()->routeIs('admin.inquiries.*') ? 'active' : '' }}">
                    <i class="bi bi-envelope-paper"></i> Wholesale Inquiries
                </a>
                @if(Auth::user() && Auth::user()->isAdmin())
                <a href="{{ route('admin.users.index') }}" class="admin-nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                    <i class="bi bi-shield-lock"></i> Users & Roles
                </a>
                @endif
            </nav>
        </div>

        <!-- User Info & Logout -->
        <div class="pt-3 border-top border-secondary border-opacity-25">
            <div class="d-flex align-items-center gap-2 mb-3">
                <div class="rounded-circle bg-gold text-dark d-flex align-items-center justify-content-center fw-bold" style="width: 36px; height: 36px; font-size: 14px;">
                    {{ strtoupper(substr(Auth::user()->name ?? 'A', 0, 1)) }}
                </div>
                <div class="min-w-0">
                    <div class="text-truncate fw-semibold text-parchment small">{{ Auth::user()->name ?? 'Administrator' }}</div>
                    <span class="badge bg-secondary text-uppercase" style="font-size: 8px; letter-spacing: 0.15em;">{{ Auth::user()->role ?? 'Admin' }}</span>
                </div>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('home') }}" target="_blank" class="btn btn-sm btn-outline-secondary w-50" title="View Storefront">
                    <i class="bi bi-eye"></i> Store
                </a>
                <form action="{{ route('admin.logout') }}" method="POST" class="w-50">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-outline-danger w-100">
                        <i class="bi bi-box-arrow-right"></i> Exit
                    </button>
                </form>
            </div>
        </div>
    </aside>

    <!-- Main Content Area -->
    <div class="admin-main">

        <!-- Top Navbar -->
        <header class="p-3 border-bottom border-secondary border-opacity-25 d-flex align-items-center justify-content-between" style="background-color: var(--admin-sidebar);">
            <div class="d-flex align-items-center gap-3">
                <button class="btn btn-outline-gold d-lg-none p-1 px-2" id="sidebarToggleBtn">
                    <i class="bi bi-list fs-5"></i>
                </button>
                <h5 class="font-heading text-gold mb-0 fw-bold">@yield('page_title', 'Dashboard')</h5>
            </div>

            <div class="d-flex align-items-center gap-3">
                <a href="{{ route('home') }}" target="_blank" class="btn-outline-gold btn-sm d-none d-sm-inline-flex text-decoration-none">
                    <i class="bi bi-shop me-1"></i> Live Store
                </a>
            </div>
        </header>

        <!-- Body Content -->
        <main class="p-3 p-md-4 flex-grow-1">

            <!-- Flash Notifications -->
            @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show bg-success bg-opacity-25 text-white border-success" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endif

            @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show bg-danger bg-opacity-25 text-white border-danger" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endif

            @if($errors->any())
            <div class="alert alert-warning alert-dismissible fade show bg-warning bg-opacity-25 text-dark border-warning" role="alert">
                <strong>Please check the form:</strong>
                <ul class="mb-0 mt-1 small">
                    @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endif

            @yield('content')
        </main>

        <!-- Admin Footer -->
        <footer class="p-3 border-top border-secondary border-opacity-25 text-center text-parchment-muted small" style="background-color: var(--admin-sidebar);">
            &copy; 2026 AZ Halal Marts · Admin Management Portal
        </footer>

    </div>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const sidebar = document.getElementById('adminSidebar');
        const toggleBtn = document.getElementById('sidebarToggleBtn');
        const closeBtn = document.getElementById('sidebarCloseBtn');

        if (toggleBtn && sidebar) {
            toggleBtn.addEventListener('click', () => sidebar.classList.toggle('show'));
        }
        if (closeBtn && sidebar) {
            closeBtn.addEventListener('click', () => sidebar.classList.remove('show'));
        }
    </script>
    @stack('scripts')
</body>
</html>
