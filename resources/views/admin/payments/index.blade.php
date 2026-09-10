@extends('admin.layouts.admin')

@section('title', 'Payments & Transactions · Admin Portal')
@section('page_title', 'Payment Transactions')

@section('content')

<!-- Payment Statistics Cards -->
<div class="row g-3 mb-4">
    <div class="col-sm-6 col-lg-3">
        <div class="admin-card p-3">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <span class="text-parchment-muted small text-uppercase" style="letter-spacing: 0.1em; font-size: 11px;">Total Volume</span>
                <i class="bi bi-cash-stack text-gold fs-5"></i>
            </div>
            <div class="font-heading fs-4 fw-bold text-gold">${{ number_format($stats['total_volume'] ?? 0, 2) }}</div>
            <span class="text-parchment-muted small">Total paid transactions</span>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="admin-card p-3">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <span class="text-parchment-muted small text-uppercase" style="letter-spacing: 0.1em; font-size: 11px;">Completed</span>
                <i class="bi bi-check-circle-fill text-success fs-5"></i>
            </div>
            <div class="font-heading fs-4 fw-bold text-parchment">{{ $stats['paid_count'] ?? 0 }}</div>
            <span class="text-parchment-muted small">Paid orders</span>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="admin-card p-3">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <span class="text-parchment-muted small text-uppercase" style="letter-spacing: 0.1em; font-size: 11px;">PayPal Express</span>
                <i class="bi bi-paypal text-info fs-5"></i>
            </div>
            <div class="font-heading fs-4 fw-bold text-info">{{ $stats['paypal_count'] ?? 0 }}</div>
            <span class="text-parchment-muted small">PayPal & Card payments</span>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="admin-card p-3">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <span class="text-parchment-muted small text-uppercase" style="letter-spacing: 0.1em; font-size: 11px;">Pending</span>
                <i class="bi bi-clock-history text-warning fs-5"></i>
            </div>
            <div class="font-heading fs-4 fw-bold text-warning">{{ $stats['pending_count'] ?? 0 }}</div>
            <span class="text-parchment-muted small">Awaiting payment</span>
        </div>
    </div>
</div>

<!-- Search & Filter Bar -->
<div class="admin-card p-3 mb-4">
    <form action="{{ route('admin.payments.index') }}" method="GET" class="row g-2 align-items-center">
        <div class="col-md-5">
            <input type="text" name="search" class="form-control" placeholder="Search order #, txn id, PayPal ID, customer..." value="{{ request('search') }}">
        </div>
        <div class="col-md-3">
            <select name="payment_status" class="form-select" onchange="this.form.submit()">
                <option value="all">All Payment Statuses</option>
                <option value="paid" {{ request('payment_status') == 'paid' ? 'selected' : '' }}>Paid</option>
                <option value="pending" {{ request('payment_status') == 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="refunded" {{ request('payment_status') == 'refunded' ? 'selected' : '' }}>Refunded</option>
                <option value="failed" {{ request('payment_status') == 'failed' ? 'selected' : '' }}>Failed</option>
            </select>
        </div>
        <div class="col-md-2">
            <select name="payment_method" class="form-select" onchange="this.form.submit()">
                <option value="all">All Methods</option>
                <option value="paypal" {{ request('payment_method') == 'paypal' ? 'selected' : '' }}>PayPal</option>
                <option value="cash_on_delivery" {{ request('payment_method') == 'cash_on_delivery' ? 'selected' : '' }}>Cash on Delivery</option>
            </select>
        </div>
        <div class="col-md-2">
            <button type="submit" class="btn btn-gold w-100">Filter</button>
        </div>
    </form>
</div>

<!-- Payments Table -->
<div class="admin-card">
    <div class="table-responsive">
        <table class="table admin-table">
            <thead>
                <tr>
                    <th>Order #</th>
                    <th>Customer</th>
                    <th>Method</th>
                    <th>Transaction / PayPal ID</th>
                    <th>Amount</th>
                    <th>Payment Status</th>
                    <th>Date Paid</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders as $order)
                <tr>
                    <td>
                        <strong class="text-gold">{{ $order->order_number }}</strong>
                    </td>
                    <td>
                        <div class="fw-semibold">{{ $order->customer_name }}</div>
                        <div class="text-parchment-muted small">{{ $order->customer_email }}</div>
                    </td>
                    <td>
                        @if($order->payment_method === 'paypal')
                        <span class="badge bg-info bg-opacity-25 text-info border border-info border-opacity-50">
                            <i class="bi bi-paypal me-1"></i> PayPal
                        </span>
                        @else
                        <span class="badge bg-secondary bg-opacity-25 text-parchment border border-secondary">
                            💵 COD
                        </span>
                        @endif
                    </td>
                    <td>
                        @if(!empty($order->transaction_id))
                        <code class="small text-gold">{{ $order->transaction_id }}</code>
                        @elseif(!empty($order->paypal_order_id))
                        <code class="small text-info">{{ $order->paypal_order_id }}</code>
                        @else
                        <span class="text-parchment-muted small">—</span>
                        @endif
                    </td>
                    <td class="font-heading fw-bold text-gold fs-6">
                        ${{ number_format($order->total_amount, 2) }}
                    </td>
                    <td>
                        <span class="badge {{ $order->getPaymentStatusBadgeClass() }} text-uppercase">
                            {{ $order->payment_status ?? 'unpaid' }}
                        </span>
                    </td>
                    <td>
                        <span class="text-parchment-dim small">
                            {{ $order->paid_at ? $order->paid_at->format('M d, Y h:i A') : ($order->created_at ? $order->created_at->format('M d, Y') : '—') }}
                        </span>
                    </td>
                    <td class="text-end">
                        <a href="{{ route('admin.payments.show', $order) }}" class="btn btn-sm btn-gold py-1 px-3">
                            View Receipt
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center py-4 text-parchment-muted">No payment transactions found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($orders->hasPages())
    <div class="p-3 border-top border-secondary border-opacity-25">
        {{ $orders->links('pagination::bootstrap-5') }}
    </div>
    @endif
</div>

@endsection
