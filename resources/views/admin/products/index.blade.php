@extends('admin.layouts.admin')

@section('title', 'Manage Products · Admin Portal')
@section('page_title', 'Foods & Products Catalog')

@section('content')

<div class="admin-card p-3 mb-4">
    <div class="row g-3 align-items-center justify-content-between">
        <div class="col-md-8">
            <form action="{{ route('admin.products.index') }}" method="GET" class="row g-2">
                <div class="col-sm-6">
                    <input type="text" name="search" class="form-control" placeholder="Search by name, SKU..." value="{{ request('search') }}">
                </div>
                <div class="col-sm-4">
                    <select name="category_id" class="form-select" onchange="this.form.submit()">
                        <option value="">All Categories</option>
                        @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-sm-2">
                    <button type="submit" class="btn btn-gold w-100">Filter</button>
                </div>
            </form>
        </div>

        <div class="col-md-4 text-md-end">
            <a href="{{ route('admin.products.create') }}" class="btn btn-gold">
                <i class="bi bi-plus-lg me-1"></i> Add Product
            </a>
        </div>
    </div>
</div>

<div class="admin-card">
    <div class="table-responsive">
        <table class="table admin-table">
            <thead>
                <tr>
                    <th>Photo</th>
                    <th>Product Details</th>
                    <th>Category</th>
                    <th>Price</th>
                    <th>Stock</th>
                    <th>Status</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($products as $p)
                <tr>
                    <td style="width: 60px;">
                        <img src="{{ $p->img ?: 'https://images.unsplash.com/photo-1558030006-450675393462?w=100' }}" alt="{{ $p->name }}" style="width: 50px; height: 50px; object-fit: cover; border-radius: 4px; border: 1px solid var(--border-gold);">
                    </td>
                    <td>
                        <strong class="text-parchment fs-6">{{ $p->name }}</strong>
                        <div class="text-parchment-muted small">SKU: <span class="text-gold">{{ $p->sku }}</span></div>
                        @if($p->badge)
                        <span class="badge bg-warning text-dark" style="font-size: 8px;">{{ $p->badge }}</span>
                        @endif
                    </td>
                    <td>
                        <span class="badge bg-dark border border-secondary">{{ $p->category->name ?? 'N/A' }}</span>
                        @if($p->subcategory)
                        <div class="text-parchment-muted small">{{ $p->subcategory->name }}</div>
                        @endif
                    </td>
                    <td class="font-heading fw-bold text-gold fs-6">${{ number_format($p->price, 2) }}</td>
                    <td>
                        @if($p->stock > 5)
                        <span class="badge bg-success">{{ $p->stock }} in stock</span>
                        @elseif($p->stock > 0)
                        <span class="badge bg-warning text-dark">{{ $p->stock }} low</span>
                        @else
                        <span class="badge bg-danger">Out of stock</span>
                        @endif
                    </td>
                    <td>
                        @if($p->is_active)
                        <span class="badge bg-success bg-opacity-25 text-success border border-success">Active</span>
                        @else
                        <span class="badge bg-secondary">Draft</span>
                        @endif
                        @if($p->is_featured)
                        <span class="badge bg-gold text-dark">Featured</span>
                        @endif
                    </td>
                    <td class="text-end">
                        <a href="{{ route('admin.products.edit', $p) }}" class="btn btn-sm btn-outline-gold py-1 px-2 me-1" title="Edit">
                            <i class="bi bi-pencil"></i>
                        </a>
                        <form action="{{ route('admin.products.destroy', $p) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this product?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger py-1 px-2" title="Delete">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center py-4 text-parchment-muted">No products found matching criteria.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="p-3 border-top border-secondary border-opacity-25">
        {{ $products->links('pagination::bootstrap-5') }}
    </div>
</div>

@endsection
