<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Services\PayPalService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    protected PayPalService $paypalService;

    public function __construct(PayPalService $paypalService)
    {
        $this->paypalService = $paypalService;
    }

    public function show()
    {
        $paypalClientId = config('services.paypal.client_id', 'sb');
        $paypalCurrency = config('services.paypal.currency', 'USD');
        $paypalMode = config('services.paypal.mode', 'sandbox');

        return view('pages.checkout', compact('paypalClientId', 'paypalCurrency', 'paypalMode'));
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
                'product_id' => is_numeric($item['id']) ? (int) $item['id'] : null,
                'product_name' => $item['name'],
                'price' => $item['price'],
                'quantity' => $item['quantity'],
                'subtotal' => $item['price'] * $item['quantity'],
                'img' => $item['img'] ?? null,
            ]);
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
        $currency = config('services.paypal.currency', 'USD');

        // If PayPal credentials configured on server, generate order via PayPal API v2
        if ($this->paypalService->isConfigured()) {
            $paypalOrder = $this->paypalService->createOrder($total, $currency, 'AZ-' . strtoupper(Str::random(8)), $validated['items']);
            if ($paypalOrder && !empty($paypalOrder['id'])) {
                return response()->json([
                    'success' => true,
                    'order_id' => $paypalOrder['id'],
                    'amount' => $total,
                    'currency' => $currency,
                ]);
            }
        }

        return response()->json([
            'success' => true,
            'amount' => $total,
            'currency' => $currency,
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
        $paypalOrderId = $validated['paypal_order_id'] ?? null;
        $transactionId = $validated['transaction_id'] ?? null;
        $payerDetails = $validated['payer_details'] ?? [];

        // If PayPal credentials are configured, verify/capture with PayPal API
        if ($this->paypalService->isConfigured() && $paypalOrderId) {
            // Attempt capture if not already captured
            if (!$transactionId) {
                $captureResult = $this->paypalService->captureOrder($paypalOrderId);
                if ($captureResult && !empty($captureResult['id'])) {
                    $transactionId = $captureResult['purchase_units'][0]['payments']['captures'][0]['id'] ?? $captureResult['id'];
                    $payerDetails = array_merge($payerDetails, $captureResult['payer'] ?? []);
                }
            } else {
                // Verify order status on PayPal
                $orderResult = $this->paypalService->getOrder($paypalOrderId);
                if ($orderResult && !empty($orderResult['payer'])) {
                    $payerDetails = array_merge($payerDetails, $orderResult['payer']);
                }
            }
        }

        $finalTransactionId = $transactionId ?: ($paypalOrderId ?: ('PP-'.strtoupper(Str::random(10))));

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
            'transaction_id' => $finalTransactionId,
            'paypal_order_id' => $paypalOrderId,
            'payment_details' => $payerDetails,
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
