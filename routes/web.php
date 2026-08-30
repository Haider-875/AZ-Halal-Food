<?php

use App\Http\Controllers\PageController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\InquiryController;
use App\Http\Controllers\Admin\AuthController as AdminAuthController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\GalleryController as AdminGalleryController;
use App\Http\Controllers\Admin\SectionController as AdminSectionController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\InquiryAdminController;
use App\Http\Middleware\AdminMiddleware;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;

// Helper route to run migrations and seeds on Vercel
Route::get('/setup-database', function () {
    try {
        Artisan::call('migrate --force');
        $migrateOutput = Artisan::output();

        Artisan::call('db:seed --force');
        $seedOutput = Artisan::output();

        return response()->json([
            'status' => 'success',
            'message' => 'Database successfully migrated and seeded on TiDB Cloud!',
            'migrate_output' => $migrateOutput,
            'seed_output' => $seedOutput,
        ]);
    } catch (\Throwable $e) {
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

        // Gallery Items CRUD
        Route::resource('gallery', AdminGalleryController::class);

        // Dynamic Website Sections & Settings
        Route::get('sections', [AdminSectionController::class, 'index'])->name('sections.index');
        Route::post('sections', [AdminSectionController::class, 'update'])->name('sections.update');

        // Inquiries Management
        Route::get('inquiries', [InquiryAdminController::class, 'index'])->name('inquiries.index');
        Route::patch('inquiries/{inquiry}/status', [InquiryAdminController::class, 'updateStatus'])->name('inquiries.status');
        Route::delete('inquiries/{inquiry}', [InquiryAdminController::class, 'destroy'])->name('inquiries.destroy');

        // User Management & Roles (Admin Only)
        Route::resource('users', AdminUserController::class);
    });
});
