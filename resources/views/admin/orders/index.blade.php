@extends('admin.layouts.admin')

@section('title', 'Order Management · Admin Portal')
@section('page_title', 'Customer Orders')

@section('content')

<div class="admin-card p-3 mb-4">
    <form action="{{ route('admin.orders.index') }}" method="GET" class="row g-2 align-items-center">
        <div class="col-md-5">
            <input type="text" name="search" class="form-control" placeholder="Search order #, customer, phone..." value="{{ request('search') }}">
        </div>
        <div class="col-md-4">
            <select name="status" class="form-select" onchange="this.form.submit()">
                <option value="all">All Statuses</option>
                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>Processing</option>
                <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
            </select>
        </div>
        <div class="col-md-3">
            <button type="submit" class="btn btn-gold w-100">Filter Orders</button>
        </div>
    </form>
</div>

<div class="admin-card">
    <div class="table-responsive">
        <table class="table admin-table">
            <thead>
                <tr>
                    <th>Order #</th>
                    <th>Customer Details</th>
                    <th>Delivery Type</th>
                    <th>Total Amount</th>
                    <th>Status</th>
                    <th>Placed At</th>
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
                        <div class="text-parchment-muted small">{{ $order->customer_phone }} · {{ $order->customer_email }}</div>
                    </td>
                    <td>
                        <span class="badge bg-dark border border-secondary text-uppercase">{{ $order->delivery_type }}</span>
                    </td>
                    <td class="font-heading fw-bold text-gold fs-6">${{ number_format($order->total_amount, 2) }}</td>
                    <td>
                        <span class="badge {{ $order->getStatusBadgeClass() }} text-uppercase">
                            {{ $order->status }}
                        </span>
                    </td>
                    <td>
                        <span class="text-parchment-dim small">{{ $order->created_at->format('M d, Y h:i A') }}</span>
                    </td>
                    <td class="text-end">
                        <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-sm btn-gold py-1 px-3">
                            Manage
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center py-4 text-parchment-muted">No orders found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="p-3 border-top border-secondary border-opacity-25">
        {{ $orders->links('pagination::bootstrap-5') }}
    </div>
</div>

@endsection
