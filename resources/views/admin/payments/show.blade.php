@extends('admin.layouts.admin')

@section('title', "Payment Receipt #{$order->order_number} · Admin Portal")
@section('page_title', "Payment Receipt #{$order->order_number}")

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <a href="{{ route('admin.payments.index') }}" class="btn-outline-gold btn-sm">
        <i class="bi bi-arrow-left me-1"></i> Back to Payments
    </a>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-sm btn-outline-secondary">
            <i class="bi bi-box me-1"></i> View Order
        </a>
        <a href="{{ route('admin.orders.invoice', $order) }}" target="_blank" class="btn btn-sm btn-gold">
            <i class="bi bi-printer me-1"></i> Print Invoice
        </a>
    </div>
</div>

<div class="row g-4">
    <!-- Payment Overview & Transaction Details -->
    <div class="col-lg-7">
        <div class="admin-card mb-4">
            <div class="p-3 border-bottom border-secondary border-opacity-25 d-flex justify-content-between align-items-center">
                <h6 class="font-heading text-gold mb-0 fw-bold">
                    <i class="bi bi-credit-card-2-front me-2"></i> Transaction Details
                </h6>
                <span class="badge {{ $order->getPaymentStatusBadgeClass() }} text-uppercase px-3 py-1">
                    {{ $order->payment_status ?? 'unpaid' }}
                </span>
            </div>

            <div class="p-4">
                <div class="row g-3">
                    <div class="col-sm-6">
                        <span class="text-parchment-muted small text-uppercase" style="letter-spacing: 0.1em; font-size: 11px;">Payment Gateway</span>
                        <div class="fw-bold mt-1 text-parchment">
                            @if($order->payment_method === 'paypal')
                            <span class="text-info"><i class="bi bi-paypal me-1"></i> PayPal Express</span>
                            @else
                            <span>💵 Cash on Delivery / Pickup</span>
                            @endif
                        </div>
                    </div>

                    <div class="col-sm-6">
                        <span class="text-parchment-muted small text-uppercase" style="letter-spacing: 0.1em; font-size: 11px;">Total Amount</span>
                        <div class="font-heading fs-4 fw-bold text-gold mt-1">
                            ${{ number_format($order->total_amount, 2) }} <span class="fs-6 text-parchment-muted">USD</span>
                        </div>
                    </div>

                    <div class="col-12 border-top border-secondary border-opacity-25 pt-3">
                        <span class="text-parchment-muted small text-uppercase" style="letter-spacing: 0.1em; font-size: 11px;">PayPal Capture / Transaction ID</span>
                        <div class="mt-1">
                            @if(!empty($order->transaction_id))
                            <code class="text-gold fs-6 bg-dark p-2 rounded d-inline-block border border-secondary border-opacity-50">
                                {{ $order->transaction_id }}
                            </code>
                            @else
                            <span class="text-parchment-muted">None recorded</span>
                            @endif
                        </div>
                    </div>

                    @if(!empty($order->paypal_order_id))
                    <div class="col-12">
                        <span class="text-parchment-muted small text-uppercase" style="letter-spacing: 0.1em; font-size: 11px;">PayPal Order Reference</span>
                        <div class="mt-1">
                            <code class="text-info fs-6 bg-dark p-2 rounded d-inline-block border border-secondary border-opacity-50">
                                {{ $order->paypal_order_id }}
                            </code>
                        </div>
                    </div>
                    @endif

                    <div class="col-sm-6">
                        <span class="text-parchment-muted small text-uppercase" style="letter-spacing: 0.1em; font-size: 11px;">Paid At</span>
                        <div class="text-parchment mt-1">
                            {{ $order->paid_at ? $order->paid_at->format('F d, Y · h:i A') : 'Pending payment' }}
                        </div>
                    </div>

                    <div class="col-sm-6">
                        <span class="text-parchment-muted small text-uppercase" style="letter-spacing: 0.1em; font-size: 11px;">Order Placed</span>
                        <div class="text-parchment mt-1">
                            {{ $order->created_at->format('F d, Y · h:i A') }}
                        </div>
                    </div>
                </div>

                @if(!empty($order->payment_details) && is_array($order->payment_details))
                <div class="border-top border-secondary border-opacity-25 mt-4 pt-3">
                    <h6 class="text-gold small text-uppercase fw-semibold mb-2">Payer Metadata</h6>
                    <div class="bg-dark bg-opacity-75 p-3 rounded border border-secondary border-opacity-25 small font-monospace">
                        @foreach($order->payment_details as $key => $val)
                        <div class="d-flex justify-content-between py-1 border-bottom border-secondary border-opacity-10">
                            <span class="text-parchment-muted">{{ ucwords(str_replace('_', ' ', $key)) }}:</span>
                            <span class="text-parchment fw-semibold text-break">{{ is_array($val) ? json_encode($val) : $val }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
        </div>

        <!-- Items Paid -->
        <div class="admin-card">
            <div class="p-3 border-bottom border-secondary border-opacity-25">
                <h6 class="font-heading text-gold mb-0 fw-bold">Purchased Items</h6>
            </div>
            <div class="table-responsive">
                <table class="table admin-table">
                    <thead>
                        <tr>
                            <th>Item</th>
                            <th>Unit</th>
                            <th>Qty</th>
                            <th class="text-end">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($order->items as $item)
                        <tr>
                            <td>
                                <strong class="text-parchment">{{ $item->product_name }}</strong>
                            </td>
                            <td>${{ number_format($item->price, 2) }}</td>
                            <td>{{ $item->quantity }}</td>
                            <td class="text-end font-heading text-gold fw-bold">${{ number_format($item->subtotal, 2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="3" class="text-end text-parchment-muted">Subtotal:</td>
                            <td class="text-end fw-semibold text-parchment">${{ number_format($order->subtotal, 2) }}</td>
                        </tr>
                        <tr>
                            <td colspan="3" class="text-end text-parchment-muted">Delivery Fee:</td>
                            <td class="text-end fw-semibold text-parchment">${{ number_format($order->delivery_fee, 2) }}</td>
                        </tr>
                        <tr>
                            <td colspan="3" class="text-end font-heading text-gold fw-bold fs-6">Grand Total:</td>
                            <td class="text-end font-heading text-gold fw-bold fs-6">${{ number_format($order->total_amount, 2) }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

    <!-- Customer & Status Controls -->
    <div class="col-lg-5">
        <!-- Update Payment Status -->
        <div class="admin-card p-4 mb-4">
            <h6 class="font-heading text-gold mb-3 fw-bold"><i class="bi bi-gear me-2"></i> Update Payment Status</h6>

            @if(session('success'))
            <div class="alert alert-success py-2 small mb-3">
                {{ session('success') }}
            </div>
            @endif

            <form action="{{ route('admin.payments.status', $order) }}" method="POST">
                @csrf
                @method('PATCH')

                <div class="mb-3">
                    <label class="form-label small text-parchment-muted">Status</label>
                    <select name="payment_status" class="form-select">
                        <option value="paid" {{ $order->payment_status === 'paid' ? 'selected' : '' }}>Paid</option>
                        <option value="pending" {{ $order->payment_status === 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="refunded" {{ $order->payment_status === 'refunded' ? 'selected' : '' }}>Refunded</option>
                        <option value="failed" {{ $order->payment_status === 'failed' ? 'selected' : '' }}>Failed</option>
                    </select>
                </div>

                <button type="submit" class="btn btn-gold w-100">
                    Update Payment Status
                </button>
            </form>
        </div>

        <!-- Customer Card -->
        <div class="admin-card p-4">
            <h6 class="font-heading text-gold mb-3 fw-bold"><i class="bi bi-person me-2"></i> Customer Details</h6>

            <div class="mb-3">
                <div class="fw-bold text-parchment fs-6">{{ $order->customer_name }}</div>
                <div class="text-parchment-muted small"><i class="bi bi-envelope me-1"></i> {{ $order->customer_email }}</div>
                <div class="text-parchment-muted small"><i class="bi bi-telephone me-1"></i> {{ $order->customer_phone }}</div>
            </div>

            <div class="border-top border-secondary border-opacity-25 pt-3">
                <span class="text-parchment-muted small text-uppercase" style="letter-spacing: 0.1em; font-size: 11px;">Delivery Address</span>
                <div class="text-parchment small mt-1">
                    {{ $order->delivery_address }}<br>
                    {{ $order->city }}, NC {{ $order->postal_code }}
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
