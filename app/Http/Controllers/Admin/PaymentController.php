<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with('items')->where(function ($q) {
            $q->whereNotNull('transaction_id')
              ->orWhereNotNull('paypal_order_id')
              ->orWhere('payment_method', 'paypal');
        });

        if ($request->filled('payment_status') && $request->payment_status !== 'all') {
            $query->where('payment_status', $request->payment_status);
        }

        if ($request->filled('payment_method') && $request->payment_method !== 'all') {
            $query->where('payment_method', $request->payment_method);
        }

        if ($request->filled('search')) {
            $s = strtolower($request->search);
            $query->where(function ($q) use ($s) {
                $q->where('order_number', 'like', "%{$s}%")
                  ->orWhere('transaction_id', 'like', "%{$s}%")
                  ->orWhere('paypal_order_id', 'like', "%{$s}%")
                  ->orWhere('customer_name', 'like', "%{$s}%")
                  ->orWhere('customer_email', 'like', "%{$s}%");
            });
        }

        $orders = $query->latest()->paginate(15)->withQueryString();

        $stats = [
            'total_volume' => Order::where('payment_status', 'paid')->sum('total_amount'),
            'paid_count' => Order::where('payment_status', 'paid')->count(),
            'paypal_count' => Order::where('payment_method', 'paypal')->where('payment_status', 'paid')->count(),
            'pending_count' => Order::where('payment_status', 'pending')->count(),
        ];

        return view('admin.payments.index', compact('orders', 'stats'));
    }

    public function show(Order $order)
    {
        $order->load('items.product');
        return view('admin.payments.show', compact('order'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $validated = $request->validate([
            'payment_status' => 'required|in:paid,pending,refunded,failed',
        ]);

        $order->update($validated);

        return back()->with('success', "Payment status for Order #{$order->order_number} updated to " . ucfirst($order->payment_status));
    }
}
