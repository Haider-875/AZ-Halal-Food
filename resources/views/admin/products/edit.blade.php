@extends('admin.layouts.admin')

@section('title', 'Edit Product · Admin Portal')
@section('page_title', 'Edit Product')

@section('content')

<div class="admin-card p-4 mx-auto" style="max-width: 900px;">
    <div class="d-flex justify-content-between align-items-center mb-4 pb-3 border-bottom border-secondary border-opacity-25">
        <div>
            <h5 class="font-heading text-gold mb-0 fw-bold">Edit: {{ $product->name }}</h5>
            <span class="text-parchment-muted small">SKU: {{ $product->sku }}</span>
        </div>
        <a href="{{ route('admin.products.index') }}" class="btn-outline-gold btn-sm">
            <i class="bi bi-arrow-left me-1"></i> Back to Products
        </a>
    </div>

    <form action="{{ route('admin.products.update', $product) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="row g-3">
            <div class="col-md-8">
                <label for="name" class="form-label small text-uppercase fw-semibold text-gold">Product Name *</label>
                <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $product->name) }}" required>
            </div>

            <div class="col-md-4">
                <label for="sku" class="form-label small text-uppercase fw-semibold text-gold">SKU Code *</label>
                <input type="text" name="sku" id="sku" class="form-control" value="{{ old('sku', $product->sku) }}" required>
            </div>

            <div class="col-md-6">
                <label for="category_id" class="form-label small text-uppercase fw-semibold text-gold">Category *</label>
                <select name="category_id" id="category_id" class="form-select" required>
                    @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" {{ old('category_id', $product->category_id) == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-6">
                <label for="subcategory_id" class="form-label small text-uppercase fw-semibold text-gold">Subcategory</label>
                <select name="subcategory_id" id="subcategory_id" class="form-select">
                    <option value="">None / General</option>
                    @foreach($categories as $cat)
                        @foreach($cat->subcategories as $sub)
                        <option value="{{ $sub->id }}" {{ old('subcategory_id', $product->subcategory_id) == $sub->id ? 'selected' : '' }}>{{ $cat->name }} → {{ $sub->name }}</option>
                        @endforeach
                    @endforeach
                </select>
            </div>

            <div class="col-md-4">
                <label for="price" class="form-label small text-uppercase fw-semibold text-gold">Price ($) *</label>
                <input type="number" step="0.01" name="price" id="price" class="form-control" value="{{ old('price', $product->price) }}" required>
            </div>

            <div class="col-md-4">
                <label for="stock" class="form-label small text-uppercase fw-semibold text-gold">Stock Quantity *</label>
                <input type="number" name="stock" id="stock" class="form-control" value="{{ old('stock', $product->stock) }}" required>
            </div>

            <div class="col-md-4">
                <label for="badge" class="form-label small text-uppercase fw-semibold text-gold">Badge Tag</label>
                <input type="text" name="badge" id="badge" class="form-control" value="{{ old('badge', $product->badge) }}">
            </div>

            <div class="col-12">
                <label for="img" class="form-label small text-uppercase fw-semibold text-gold">Image URL</label>
                <input type="url" name="img" id="img" class="form-control" value="{{ old('img', $product->img) }}">
            </div>

            <div class="col-12">
                <label for="desc" class="form-label small text-uppercase fw-semibold text-gold">Product Description</label>
                <textarea name="desc" id="desc" rows="3" class="form-control">{{ old('desc', $product->desc) }}</textarea>
            </div>

            <div class="col-12 d-flex gap-4 pt-2">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="is_featured" id="is_featured" value="1" {{ old('is_featured', $product->is_featured) ? 'checked' : '' }}>
                    <label class="form-check-label text-parchment" for="is_featured">Show in Featured Selections</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $product->is_active) ? 'checked' : '' }}>
                    <label class="form-check-label text-parchment" for="is_active">Active & Visible in Store</label>
                </div>
            </div>

            <div class="col-12 pt-4 border-top border-secondary border-opacity-25">
                <button type="submit" class="btn btn-gold px-4 py-2">Update Product</button>
            </div>
        </div>
    </form>
</div>

@endsection
