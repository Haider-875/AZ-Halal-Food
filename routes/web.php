<?php

use App\Http\Controllers\Admin\AuthController as AdminAuthController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\GalleryController as AdminGalleryController;
use App\Http\Controllers\Admin\InquiryAdminController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\PaymentController as AdminPaymentController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\ProfileController as AdminProfileController;
use App\Http\Controllers\Admin\SectionController as AdminSectionController;
use App\Http\Controllers\Admin\SliderController as AdminSliderController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\InquiryController;
use App\Http\Controllers\PageController;
use App\Http\Middleware\AdminMiddleware;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Live Server Maintenance Routes
|--------------------------------------------------------------------------
| Hit /deploy after every code push to your live server.
| Hit /optimize-clear only if something is broken / cached incorrectly.
*/

// ── FULL DEPLOY ─────────────────────────────────────────────────────────────
// Run after every git pull / code push on the live server.
// Order: clear old caches → migrate → rebuild all caches → optimize
Route::get('/deploy', function () {
    $steps = [];
    $start = microtime(true);

    $run = function (string $cmd) use (&$steps) {
        $t = microtime(true);
        Artisan::call($cmd);
        $steps[] = [
            'cmd' => $cmd,
            'output' => trim(Artisan::output()) ?: 'OK',
            'ms' => round((microtime(true) - $t) * 1000),
        ];
    };

    try {
        // 1. Clear all old caches first (safe start)
        $run('optimize:clear');

        // 2. Run any pending database migrations
        $run('migrate --force');

        // 3. Cache config (reads .env → PHP array, eliminates .env parsing per request)
        $run('config:cache');

        // 4. Cache routes (eliminates route registration on every request)
        $run('route:cache');

        // 5. Cache all Blade views (eliminates Blade compilation on every request)
        $run('view:cache');

        // 6. Cache event/listener map
        $run('event:cache');

        // 7. Clear application-level cache (products, sessions etc. stale data)
        $run('cache:clear');

        return response()->json([
            'status' => 'success',
            'message' => 'Deploy complete — all caches rebuilt.',
            'total_ms' => round((microtime(true) - $start) * 1000),
            'steps' => $steps,
        ]);
    } catch (Throwable $e) {
        return response()->json([
            'status' => 'error',
            'message' => $e->getMessage(),
            'steps' => $steps,
        ], 500);
    }
});

// ── OPTIMIZE CLEAR (Emergency reset) ────────────────────────────────────────
// Use when the site is serving stale/broken pages.
// Clears everything then rebuilds all caches.
Route::get('/optimize-clear', function () {
    $steps = [];
    $start = microtime(true);

    $run = function (string $cmd) use (&$steps) {
        $t = microtime(true);
        Artisan::call($cmd);
        $steps[] = [
            'cmd' => $cmd,
            'output' => trim(Artisan::output()) ?: 'OK',
            'ms' => round((microtime(true) - $t) * 1000),
        ];
    };

    try {
        // Clear phase
        $run('optimize:clear');   // clears config, route, view, event caches
        $run('cache:clear');      // clears application cache (file/database)
        $run('view:clear');       // clears compiled Blade templates

        // Rebuild phase
        $run('config:cache');     // re-cache .env + config/
        $run('route:cache');      // re-cache all routes
        $run('view:cache');       // re-compile all Blade views
        $run('event:cache');      // re-cache event listeners

        return response()->json([
            'status' => 'success',
            'message' => 'All caches cleared and rebuilt.',
            'total_ms' => round((microtime(true) - $start) * 1000),
            'steps' => $steps,
        ]);
    } catch (Throwable $e) {
        return response()->json([
            'status' => 'error',
            'message' => $e->getMessage(),
            'steps' => $steps,
        ], 500);
    }
});

// ── OPTIMIZE CACHE (Rebuild only, no clear) ──────────────────────────────────
// Use after editing a .blade.php or config file without a full deploy.
Route::get('/optimize-cache', function () {
    $steps = [];
    $start = microtime(true);

    $run = function (string $cmd) use (&$steps) {
        $t = microtime(true);
        Artisan::call($cmd);
        $steps[] = [
            'cmd' => $cmd,
            'output' => trim(Artisan::output()) ?: 'OK',
            'ms' => round((microtime(true) - $t) * 1000),
        ];
    };

    try {
        $run('config:cache');
        $run('route:cache');
        $run('view:cache');
        $run('event:cache');

        return response()->json([
            'status' => 'success',
            'message' => 'All caches rebuilt for production.',
            'total_ms' => round((microtime(true) - $start) * 1000),
            'steps' => $steps,
        ]);
    } catch (Throwable $e) {
        return response()->json([
            'status' => 'error',
            'message' => $e->getMessage(),
            'steps' => $steps,
        ], 500);
    }
});

// ── CLEAR CACHE ONLY ─────────────────────────────────────────────────────────
// Use to bust stale product/category data from the application cache.
Route::get('/clear-cache', function () {
    try {
        Artisan::call('cache:clear');

        return response()->json([
            'status' => 'success',
            'command' => 'cache:clear',
            'output' => trim(Artisan::output()) ?: 'OK',
        ]);
    } catch (Throwable $e) {
        return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
    }
});

// ── SETUP DATABASE (migrate + seed) ──────────────────────────────────────────
// Run once on first deploy to a new server.
Route::get('/setup-database', function () {
    try {
        Artisan::call('migrate --force');
        $migrateOutput = trim(Artisan::output());

        Artisan::call('db:seed --force');
        $seedOutput = trim(Artisan::output());

        return response()->json([
            'status' => 'success',
            'message' => 'Database migrated and seeded.',
            'migrate_output' => $migrateOutput,
            'seed_output' => $seedOutput,
        ]);
    } catch (Throwable $e) {
        return response()->json([
            'status' => 'error',
            'message' => $e->getMessage(),
        ], 500);
    }
});

// Public Customer Website Routes
Route::get('/', [PageController::class, 'home'])->name('home');
Route::get('/about', [PageController::class, 'about'])->name('about');
Route::get('/products', [PageController::class, 'products'])->name('products');
Route::get('/catalog', [PageController::class, 'catalog'])->name('catalog');
Route::get('/gallery', [PageController::class, 'gallery'])->name('gallery');
Route::get('/contact', [PageController::class, 'contact'])->name('contact');
Route::post('/contact/inquiry', [InquiryController::class, 'store'])->name('inquiry.store');

// Checkout & Order Placement Routes
Route::get('/checkout', [CheckoutController::class, 'show'])->name('checkout');
Route::post('/checkout', [CheckoutController::class, 'process'])->name('checkout.process');
Route::post('/checkout/paypal/create', [CheckoutController::class, 'createPaypalOrder'])->name('checkout.paypal.create');
Route::post('/checkout/paypal/capture', [CheckoutController::class, 'capturePaypalOrder'])->name('checkout.paypal.capture');
Route::get('/checkout-success', [PageController::class, 'checkoutSuccess'])->name('checkout.success');

// Standard Login Fallback
Route::get('/login', [AdminAuthController::class, 'showLogin'])->name('login');

// Admin Authentication Routes
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [AdminAuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AdminAuthController::class, 'login'])->name('login.submit');
    Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');

    // Protected Admin Routes
    Route::middleware([AdminMiddleware::class])->group(function () {
        Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');
        Route::get('/dashboard', [AdminDashboardController::class, 'index']);

        // Products CRUD
        Route::resource('products', AdminProductController::class);

        // Categories & Subcategories CRUD
        Route::resource('categories', AdminCategoryController::class);
        Route::post('categories/subcategories', [AdminCategoryController::class, 'storeSubcategory'])->name('categories.subcategories.store');
        Route::delete('categories/subcategories/{subcategory}', [AdminCategoryController::class, 'destroySubcategory'])->name('categories.subcategories.destroy');

        // Orders Management
        Route::get('orders', [AdminOrderController::class, 'index'])->name('orders.index');
        Route::get('orders/{order}', [AdminOrderController::class, 'show'])->name('orders.show');
        Route::patch('orders/{order}/status', [AdminOrderController::class, 'updateStatus'])->name('orders.status');
        Route::get('orders/{order}/invoice', [AdminOrderController::class, 'printInvoice'])->name('orders.invoice');

        // Payments & Transactions Screen
        Route::get('payments', [AdminPaymentController::class, 'index'])->name('payments.index');
        Route::get('payments/{order}', [AdminPaymentController::class, 'show'])->name('payments.show');
        Route::patch('payments/{order}/status', [AdminPaymentController::class, 'updateStatus'])->name('payments.status');

        // Gallery Items CRUD
        Route::resource('gallery', AdminGalleryController::class);

        // Hero Sliders CRUD
        Route::resource('sliders', AdminSliderController::class);

        // Dynamic Website Sections & Settings
        Route::get('sections', [AdminSectionController::class, 'index'])->name('sections.index');
        Route::post('sections', [AdminSectionController::class, 'update'])->name('sections.update');

        // Inquiries Management
        Route::get('inquiries', [InquiryAdminController::class, 'index'])->name('inquiries.index');
        Route::patch('inquiries/{inquiry}/status', [InquiryAdminController::class, 'updateStatus'])->name('inquiries.status');
        Route::delete('inquiries/{inquiry}', [InquiryAdminController::class, 'destroy'])->name('inquiries.destroy');

        // My Profile & Password Management
        Route::get('profile', [AdminProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('profile', [AdminProfileController::class, 'update'])->name('profile.update');
        Route::put('profile/password', [AdminProfileController::class, 'updatePassword'])->name('profile.password');

        // User Management & Roles (Admin Only)
        Route::middleware([AdminMiddleware::class.':admin'])->group(function () {
            Route::resource('users', AdminUserController::class);
            Route::put('users/{user}/password', [AdminUserController::class, 'updatePassword'])->name('users.password');
        });
    });
});
