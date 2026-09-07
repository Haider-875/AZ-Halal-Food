<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    public function show()
    {
        return view('pages.checkout');
    }

    public function process(Request $request)
    {
        $validated = $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email|max:255',
            'customer_phone' => 'required|string|max:50',
            'delivery_address' => 'required_if:delivery_type,delivery|nullable|string|max:500',
            'city' => 'nullable|string|max:100',
            'postal_code' => 'nullable|string|max:20',
            'delivery_type' => 'required|in:pickup,delivery',
            'payment_method' => 'required|in:cash_on_delivery,card,online_payment,online',
            'notes' => 'nullable|string|max:1000',
            'items' => 'required|array|min:1',
            'items.*.id' => 'required|integer',
            'items.*.id' => 'required',
            'items.*.name' => 'required|string',
            'items.*.price' => 'required|numeric',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.img' => 'nullable|string',
        ]);

        $subtotal = 0;
        foreach ($validated['items'] as $item) {
            $subtotal += ($item['price'] * $item['quantity']);
        }

        $deliveryFee = ($validated['delivery_type'] === 'delivery' && $subtotal < 50) ? 5.00 : 0.00;
        $totalAmount = $subtotal + $deliveryFee;

        $orderNumber = 'AZ-'.strtoupper(Str::random(8));

        $order = Order::create([
            'order_number' => $orderNumber,
            'customer_name' => $validated['customer_name'],
            'customer_email' => $validated['customer_email'],
            'customer_phone' => $validated['customer_phone'],
            'delivery_address' => $validated['delivery_address'] ?? 'Store Pickup (716 Slash Pine Dr)',
            'city' => $validated['city'] ?? 'Cary',
            'postal_code' => $validated['postal_code'] ?? '27519',
            'delivery_type' => $validated['delivery_type'],
            'payment_method' => $validated['payment_method'],
            'subtotal' => $subtotal,
            'delivery_fee' => $deliveryFee,
            'total_amount' => $totalAmount,
            'status' => 'pending',
            'notes' => $validated['notes'] ?? null,
        ]);

        foreach ($validated['items'] as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item['id'],
                'product_id' => is_numeric($item['id']) ? (int) $item['id'] : null,
                'product_name' => $item['name'],
                'price' => $item['price'],
                'quantity' => $item['quantity'],
                'subtotal' => $item['price'] * $item['quantity'],
                'img' => $item['img'] ?? null,
            ]);

            // Stock management is bypassed for now per request
            // Product::where('id', $item['id'])->decrement('stock', $item['quantity']);
        }

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'order_number' => $orderNumber,
                'redirect' => route('checkout.success', ['order' => $orderNumber]),
            ]);
        }

        return redirect()->route('checkout.success', ['order' => $orderNumber]);
    }

    public function createPaypalOrder(Request $request)
    {
        $validated = $request->validate([
            'items' => 'required|array|min:1',
            'items.*.price' => 'required|numeric',
            'items.*.quantity' => 'required|integer|min:1',
            'delivery_type' => 'nullable|in:pickup,delivery',
        ]);

        $subtotal = 0;
        foreach ($validated['items'] as $item) {
            $subtotal += ($item['price'] * $item['quantity']);
        }

        $deliveryFee = (($validated['delivery_type'] ?? 'delivery') === 'delivery' && $subtotal < 50) ? 5.00 : 0.00;
        $total = round($subtotal + $deliveryFee, 2);

        return response()->json([
            'success' => true,
            'amount' => $total,
            'currency' => config('services.paypal.currency', 'USD'),
        ]);
    }

    public function capturePaypalOrder(Request $request)
    {
        $validated = $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email|max:255',
            'customer_phone' => 'required|string|max:50',
            'delivery_address' => 'nullable|string|max:500',
            'city' => 'nullable|string|max:100',
            'postal_code' => 'nullable|string|max:20',
            'delivery_type' => 'nullable|in:pickup,delivery',
            'notes' => 'nullable|string|max:1000',
            'transaction_id' => 'nullable|string|max:100',
            'paypal_order_id' => 'nullable|string|max:100',
            'payer_details' => 'nullable|array',
            'items' => 'required|array|min:1',
            'items.*.id' => 'required|integer',
            'items.*.id' => 'required',
            'items.*.name' => 'required|string',
            'items.*.price' => 'required|numeric',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.img' => 'nullable|string',
        ]);

        $subtotal = 0;
        foreach ($validated['items'] as $item) {
            $subtotal += ($item['price'] * $item['quantity']);
        }

        $deliveryType = $validated['delivery_type'] ?? 'delivery';
        $deliveryFee = ($deliveryType === 'delivery' && $subtotal < 50) ? 5.00 : 0.00;
        $totalAmount = $subtotal + $deliveryFee;

        $orderNumber = 'AZ-'.strtoupper(Str::random(8));
        $transactionId = $validated['transaction_id'] ?? $validated['paypal_order_id'] ?? ('PP-'.strtoupper(Str::random(10)));

        $order = Order::create([
            'order_number' => $orderNumber,
            'customer_name' => $validated['customer_name'],
            'customer_email' => $validated['customer_email'],
            'customer_phone' => $validated['customer_phone'],
            'delivery_address' => $validated['delivery_address'] ?? 'Store Pickup (716 Slash Pine Dr)',
            'city' => $validated['city'] ?? 'Cary',
            'postal_code' => $validated['postal_code'] ?? '27519',
            'delivery_type' => $deliveryType,
            'payment_method' => 'paypal',
            'payment_status' => 'paid',
            'transaction_id' => $transactionId,
            'paypal_order_id' => $validated['paypal_order_id'] ?? null,
            'payment_details' => $validated['payer_details'] ?? [],
            'paid_at' => now(),
            'subtotal' => $subtotal,
            'delivery_fee' => $deliveryFee,
            'total_amount' => $totalAmount,
            'status' => 'processing',
            'notes' => $validated['notes'] ?? null,
        ]);

        foreach ($validated['items'] as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item['id'],
                'product_id' => is_numeric($item['id']) ? (int) $item['id'] : null,
                'product_name' => $item['name'],
                'price' => $item['price'],
                'quantity' => $item['quantity'],
                'subtotal' => $item['price'] * $item['quantity'],
                'img' => $item['img'] ?? null,
            ]);
        }

        return response()->json([
            'success' => true,
            'order_number' => $orderNumber,
            'redirect' => route('checkout.success', ['order' => $orderNumber]),
        ]);
    }
}
