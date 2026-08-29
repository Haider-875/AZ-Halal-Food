<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Receipt Invoice · {{ $order->order_number }}</title>
    <style>
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; color: #333; margin: 40px; }
        .header { display: flex; justify-content: space-between; border-bottom: 2px solid #D4AF37; padding-bottom: 20px; margin-bottom: 30px; }
        .title { font-size: 24px; font-weight: bold; color: #0D170D; }
        .invoice-details { text-align: right; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th { background: #f8f9fa; border-bottom: 2px solid #dee2e6; text-align: left; padding: 10px; font-size: 12px; }
        td { border-bottom: 1px solid #eee; padding: 10px; font-size: 13px; }
        .total-row { font-weight: bold; font-size: 16px; color: #0D170D; }
        .print-btn { background: #D4AF37; color: #fff; border: none; padding: 10px 20px; cursor: pointer; border-radius: 4px; font-weight: bold; }
        @media print { .no-print { display: none; } }
    </style>
</head>
<body>

<div class="no-print" style="margin-bottom: 20px;">
    <button class="print-btn" onclick="window.print()">Print Receipt</button>
</div>

<div class="header">
    <div>
        <div class="title">AZ HALAL MARTS</div>
        <div style="font-size: 12px; color: #666; margin-top: 5px;">
            716 Slash Pine Dr, Cary, NC 27519<br>
            Phone: 919-244-8634 | 919-344-1125<br>
            100% Hand-Cut Zabiha Halal
        </div>
    </div>
    <div class="invoice-details">
        <h2 style="margin: 0; color: #D4AF37;">INVOICE</h2>
        <div style="font-size: 13px; margin-top: 5px;">
            <strong>Order #:</strong> {{ $order->order_number }}<br>
            <strong>Date:</strong> {{ $order->created_at->format('M d, Y h:i A') }}<br>
            <strong>Status:</strong> {{ strtoupper($order->status) }}
        </div>
    </div>
</div>

<div style="display: flex; justify-content: space-between; margin-bottom: 30px; font-size: 13px;">
    <div>
        <strong>Customer Information:</strong><br>
        {{ $order->customer_name }}<br>
        {{ $order->customer_phone }}<br>
        {{ $order->customer_email }}
    </div>
    <div style="text-align: right;">
        <strong>Delivery & Method:</strong><br>
        {{ strtoupper($order->delivery_type) }}<br>
        {{ $order->delivery_address }}<br>
        {{ $order->city }} {{ $order->postal_code }}
    </div>
</div>

<table>
    <thead>
        <tr>
            <th>Item</th>
            <th>Price</th>
            <th>Qty</th>
            <th style="text-align: right;">Total</th>
        </tr>
    </thead>
    <tbody>
        @foreach($order->items as $item)
        <tr>
            <td>{{ $item->product_name }}</td>
            <td>${{ number_format($item->price, 2) }}</td>
            <td>{{ $item->quantity }}</td>
            <td style="text-align: right;">${{ number_format($item->subtotal, 2) }}</td>
        </tr>
        @endforeach
        <tr>
            <td colspan="3" style="text-align: right;">Subtotal:</td>
            <td style="text-align: right;">${{ number_format($order->subtotal, 2) }}</td>
        </tr>
        <tr>
            <td colspan="3" style="text-align: right;">Delivery Fee:</td>
            <td style="text-align: right;">${{ number_format($order->delivery_fee, 2) }}</td>
        </tr>
        <tr class="total-row">
            <td colspan="3" style="text-align: right; border-top: 2px solid #333;">Total Paid / Due:</td>
            <td style="text-align: right; border-top: 2px solid #333;">${{ number_format($order->total_amount, 2) }}</td>
        </tr>
    </tbody>
</table>

@if($order->notes)
<div style="margin-top: 30px; padding: 15px; background: #f8f9fa; border-left: 4px solid #D4AF37; font-size: 12px;">
    <strong>Special Instructions:</strong><br>
    {{ $order->notes }}
</div>
@endif

<div style="margin-top: 50px; text-align: center; font-size: 11px; color: #888;">
    Thank you for choosing AZ Halal Marts! May your meals be blessed with flavor and goodness.
</div>

</body>
</html>
