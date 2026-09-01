@extends('admin.layouts.admin')

@section('title', "Order #{$order->order_number} · Admin Portal")
@section('page_title', "Order Details #{$order->order_number}")

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <a href="{{ route('admin.orders.index') }}" class="btn-outline-gold btn-sm">
        <i class="bi bi-arrow-left me-1"></i> Back to Orders
    </a>
    <a href="{{ route('admin.orders.invoice', $order) }}" target="_blank" class="btn btn-sm btn-outline-secondary">
        <i class="bi bi-printer me-1"></i> Print Receipt Invoice
    </a>
</div>

<div class="row g-4">
    <!-- Order Items & Summary -->
    <div class="col-lg-8">
        <div class="admin-card mb-4">
            <div class="p-3 border-bottom border-secondary border-opacity-25">
                <h6 class="font-heading text-gold mb-0 fw-bold"><i class="bi bi-box me-2"></i> Ordered Items ({{ $order->items->count() }})</h6>
            </div>

            <div class="table-responsive">
                <table class="table admin-table">
                    <thead>
                        <tr>
                            <th>Item</th>
                            <th>Unit Price</th>
                            <th>Qty</th>
                            <th class="text-end">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($order->items as $item)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center gap-3">
                                    @if($item->img)
                                    <img src="{{ $item->img }}" alt="{{ $item->product_name }}" style="width: 45px; height: 45px; object-fit: cover; border-radius: 4px;">
                                    @endif
                                    <strong class="text-parchment">{{ $item->product_name }}</strong>
                                </div>
                            </td>
                            <td>${{ number_format($item->price, 2) }}</td>
                            <td><span class="badge bg-dark border border-secondary">{{ $item->quantity }}</span></td>
                            <td class="text-end font-heading text-gold fw-bold">${{ number_format($item->subtotal, 2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="3" class="text-end text-parchment-muted">Items Subtotal:</td>
                            <td class="text-end fw-bold text-parchment">${{ number_format($order->subtotal, 2) }}</td>
                        </tr>
                        <tr>
                            <td colspan="3" class="text-end text-parchment-muted">Delivery Fee:</td>
                            <td class="text-end fw-bold text-parchment">${{ number_format($order->delivery_fee, 2) }}</td>
                        </tr>
                        <tr class="border-top border-gold">
                            <td colspan="3" class="text-end font-heading fs-5 text-gold">Total Amount:</td>
                            <td class="text-end font-heading fs-5 text-gold fw-bold">${{ number_format($order->total_amount, 2) }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        @if($order->notes)
        <div class="admin-card p-3 mb-4">
            <h6 class="font-heading text-gold mb-2 fw-bold"><i class="bi bi-chat-left-text me-2"></i> Customer Cutting / Delivery Notes</h6>
            <p class="text-parchment-dim mb-0 small">{{ $order->notes }}</p>
        </div>
        @endif
    </div>

    <!-- Customer & Status Updater Sidebar -->
    <div class="col-lg-4 d-flex flex-column gap-4">

        <!-- Status Updater -->
        <div class="admin-card p-3">
            <h6 class="font-heading text-gold mb-3 fw-bold"><i class="bi bi-sliders me-2"></i> Update Order Status</h6>
            <form action="{{ route('admin.orders.status', $order) }}" method="POST">
                @csrf
                @method('PATCH')

                <div class="mb-3">
                    <label for="status" class="form-label small text-uppercase text-gold">Status</label>
                    <select name="status" id="status" class="form-select">
                        <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>Processing (Preparing)</option>
                        <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>Completed (Fulfilled)</option>
                        <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                </div>

                <button type="submit" class="btn btn-gold w-100">Save Status</button>
            </form>
        </div>

        <!-- Customer & Delivery Info -->
        <div class="admin-card p-3">
            <h6 class="font-heading text-gold mb-3 fw-bold"><i class="bi bi-person-lines-fill me-2"></i> Customer Info</h6>
            <div class="mb-2">
                <span class="text-parchment-muted small">Name:</span>
                <div class="fw-semibold">{{ $order->customer_name }}</div>
            </div>
            <div class="mb-2">
                <span class="text-parchment-muted small">Phone:</span>
                <div><a href="tel:{{ $order->customer_phone }}" class="text-gold text-decoration-none">{{ $order->customer_phone }}</a></div>
            </div>
            <div class="mb-2">
                <span class="text-parchment-muted small">Email:</span>
                <div><a href="mailto:{{ $order->customer_email }}" class="text-parchment-dim text-decoration-none">{{ $order->customer_email }}</a></div>
            </div>
            <div class="mb-2">
                <span class="text-parchment-muted small">Address:</span>
                <div class="text-parchment-dim">{{ $order->delivery_address }}, {{ $order->city }} {{ $order->postal_code }}</div>
            </div>
            <div class="mb-0">
                <span class="text-parchment-muted small">Payment:</span>
                <div class="badge bg-secondary text-uppercase">{{ str_replace('_', ' ', $order->payment_method) }}</div>
            </div>
        </div>

    </div>
</div>

@endsection
