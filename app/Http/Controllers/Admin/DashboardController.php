<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Inquiry;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\Cache;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = Cache::remember('admin_dashboard_stats', 60, function () {
            return [
                'total_orders' => Order::count(),
                'pending_orders' => Order::where('status', 'pending')->count(),
                'processing_orders' => Order::where('status', 'processing')->count(),
                'total_revenue' => (float) Order::where('status', '!=', 'cancelled')->sum('total_amount'),
                'total_products' => Product::count(),
                'low_stock_products' => Product::where('stock', '<=', 5)->count(),
                'total_categories' => Category::count(),
                'new_inquiries' => Inquiry::where('status', 'new')->count(),
                'total_users' => User::count(),
            ];
        });

        $recentOrders = Order::with('items')->latest()->take(8)->get();
        $recentInquiries = Inquiry::latest()->take(5)->get();
        $lowStockItems = Product::where('stock', '<=', 5)->take(5)->get();

        return view('admin.dashboard', compact('stats', 'recentOrders', 'recentInquiries', 'lowStockItems'));
    }
}
