@extends('admin.layouts.admin')

@section('title', 'Admin Dashboard · AZ Halal Marts')
@section('page_title', 'Dashboard Overview')

@section('content')

<!-- Metric Cards -->
<div class="row g-3 mb-4">
    <div class="col-sm-6 col-xl-3">
        <div class="admin-card p-3 d-flex align-items-center justify-content-between">
            <div>
                <span class="text-parchment-muted text-uppercase small" style="font-size: 11px; letter-spacing: 0.1em;">Total Revenue</span>
                <h3 class="font-heading text-gold fw-bold mb-0 mt-1">${{ number_format($stats['total_revenue'], 2) }}</h3>
                <span class="text-success small"><i class="bi bi-graph-up"></i> Lifetime Sales</span>
            </div>
            <div class="rounded p-3" style="background: rgba(212, 175, 55, 0.15); color: var(--gold);">
                <i class="bi bi-currency-dollar fs-3"></i>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-xl-3">
        <div class="admin-card p-3 d-flex align-items-center justify-content-between">
            <div>
                <span class="text-parchment-muted text-uppercase small" style="font-size: 11px; letter-spacing: 0.1em;">Pending Orders</span>
                <h3 class="font-heading text-warning fw-bold mb-0 mt-1">{{ $stats['pending_orders'] }}</h3>
                <span class="text-parchment-dim small">{{ $stats['total_orders'] }} total orders</span>
            </div>
            <div class="rounded p-3" style="background: rgba(255, 193, 7, 0.15); color: #ffc107;">
                <i class="bi bi-clock-history fs-3"></i>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-xl-3">
        <div class="admin-card p-3 d-flex align-items-center justify-content-between">
            <div>
                <span class="text-parchment-muted text-uppercase small" style="font-size: 11px; letter-spacing: 0.1em;">Active Products</span>
                <h3 class="font-heading text-parchment fw-bold mb-0 mt-1">{{ $stats['total_products'] }}</h3>
                @if($stats['low_stock_products'] > 0)
                <span class="text-danger small"><i class="bi bi-exclamation-circle"></i> {{ $stats['low_stock_products'] }} low stock</span>
                @else
                <span class="text-success small">All well-stocked</span>
                @endif
            </div>
            <div class="rounded p-3" style="background: rgba(13, 110, 253, 0.15); color: #0d6efd;">
                <i class="bi bi-box-seam fs-3"></i>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-xl-3">
        <div class="admin-card p-3 d-flex align-items-center justify-content-between">
            <div>
                <span class="text-parchment-muted text-uppercase small" style="font-size: 11px; letter-spacing: 0.1em;">New Inquiries</span>
                <h3 class="font-heading text-gold-light fw-bold mb-0 mt-1">{{ $stats['new_inquiries'] }}</h3>
                <span class="text-parchment-dim small">Wholesale & Catering</span>
            </div>
            <div class="rounded p-3" style="background: rgba(40, 167, 69, 0.15); color: #28a745;">
                <i class="bi bi-chat-left-dots fs-3"></i>
            </div>
        </div>
    </div>
</div>

<!-- Main Content Grid -->
<div class="row g-4">
    <!-- Recent Orders Table -->
    <div class="col-lg-8">
        <div class="admin-card">
            <div class="p-3 border-bottom border-secondary border-opacity-25 d-flex align-items-center justify-content-between">
                <h6 class="font-heading text-gold mb-0 fw-bold"><i class="bi bi-receipt me-2"></i> Recent Customer Orders</h6>
                <a href="{{ route('admin.orders.index') }}" class="btn-outline-gold btn-sm py-1">View All</a>
            </div>

            <div class="table-responsive">
                <table class="table admin-table">
                    <thead>
                        <tr>
                            <th>Order #</th>
                            <th>Customer</th>
                            <th>Items</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentOrders as $order)
                        <tr>
                            <td>
                                <strong class="text-gold">{{ $order->order_number }}</strong><br>
                                <span class="text-parchment-muted" style="font-size: 10px;">{{ $order->created_at->format('M d, h:i A') }}</span>
                            </td>
                            <td>
                                <div class="fw-semibold">{{ $order->customer_name }}</div>
                                <span class="text-parchment-muted small">{{ $order->customer_phone }}</span>
                            </td>
                            <td>{{ $order->items->count() }} item(s)</td>
                            <td class="font-heading fw-bold text-gold">${{ number_format($order->total_amount, 2) }}</td>
                            <td>
                                <span class="badge {{ $order->getStatusBadgeClass() }} text-uppercase" style="font-size: 9px; letter-spacing: 0.1em;">
                                    {{ $order->status }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-sm btn-outline-secondary py-0 px-2" title="View Order">
                                    <i class="bi bi-eye"></i>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-4 text-parchment-muted">No orders placed yet.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Quick Action / Alerts Sidebar -->
    <div class="col-lg-4 d-flex flex-column gap-4">
        
        <!-- Low Stock Alerts -->
        <div class="admin-card">
            <div class="p-3 border-bottom border-secondary border-opacity-25">
                <h6 class="font-heading text-gold mb-0 fw-bold"><i class="bi bi-exclamation-diamond me-2"></i> Low Stock Alerts</h6>
            </div>
            <div class="p-3">
                @forelse($lowStockItems as $item)
                <div class="d-flex align-items-center justify-content-between py-2 border-bottom border-secondary border-opacity-10">
                    <div class="min-w-0">
                        <div class="text-truncate fw-semibold" style="font-size: 13px;">{{ $item->name }}</div>
                        <span class="text-parchment-muted" style="font-size: 11px;">SKU: {{ $item->sku }}</span>
                    </div>
                    <span class="badge bg-danger ms-2">{{ $item->stock }} left</span>
                </div>
                @empty
                <p class="text-parchment-muted small mb-0">All products have sufficient stock levels.</p>
                @endforelse
            </div>
        </div>

        <!-- Recent Inquiries -->
        <div class="admin-card">
            <div class="p-3 border-bottom border-secondary border-opacity-25 d-flex align-items-center justify-content-between">
                <h6 class="font-heading text-gold mb-0 fw-bold"><i class="bi bi-chat-quote me-2"></i> Recent Inquiries</h6>
                <a href="{{ route('admin.inquiries.index') }}" class="btn-outline-gold btn-sm py-1" style="font-size: 10px;">All</a>
            </div>
            <div class="p-3">
                @forelse($recentInquiries as $inq)
                <div class="py-2 border-bottom border-secondary border-opacity-10">
                    <div class="d-flex justify-content-between align-items-center">
                        <strong class="text-parchment small">{{ $inq->name }}</strong>
                        <span class="badge bg-secondary" style="font-size: 9px;">{{ $inq->inquiry_type }}</span>
                    </div>
                    <p class="text-parchment-dim small text-truncate mb-0 mt-1">{{ $inq->message }}</p>
                </div>
                @empty
                <p class="text-parchment-muted small mb-0">No new inquiries received.</p>
                @endforelse
            </div>
        </div>

    </div>
</div>

@endsection
