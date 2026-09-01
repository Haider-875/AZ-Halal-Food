@extends('admin.layouts.admin')

@section('title', 'Add New Product · Admin Portal')
@section('page_title', 'Create Product')

@section('content')

<div class="admin-card p-4 mx-auto" style="max-width: 900px;">
    <div class="d-flex justify-content-between align-items-center mb-4 pb-3 border-bottom border-secondary border-opacity-25">
        <div>
            <h5 class="font-heading text-gold mb-1 fw-bold">New Food / Product Item</h5>
            <p class="text-parchment-muted small mb-0">Upload a product photo from your computer or provide an image link.</p>
        </div>
        <a href="{{ route('admin.products.index') }}" class="btn-outline-gold btn-sm">
            <i class="bi bi-arrow-left me-1"></i> Back to Products
        </a>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show bg-danger bg-opacity-25 text-white border-danger mb-4" role="alert">
            <strong><i class="bi bi-exclamation-triangle me-2"></i> Please correct the following errors:</strong>
            <ul class="mb-0 mt-2 small">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="row g-3">
            <!-- Image Upload Box -->
            <div class="col-12">
                <div class="p-3 rounded border border-secondary border-opacity-25 mb-3" style="background: rgba(13, 23, 13, 0.5);">
                    <label class="form-label text-uppercase fw-semibold text-gold small d-flex align-items-center gap-2 mb-2">
                        <i class="bi bi-image"></i> Product Image
                    </label>
                    <p class="text-parchment-muted small mb-3">
                        Upload a photo from your computer (JPG, PNG, WEBP up to 5MB) or enter an external image URL.
                    </p>

                    <div class="row g-3">
                        <!-- File Upload -->
                        <div class="col-md-6">
                            <label for="image_file" class="form-label small text-parchment fw-semibold">
                                <i class="bi bi-cloud-arrow-up text-gold me-1"></i> Upload Image File
                            </label>
                            <input type="file" name="image_file" id="image_file" class="form-control" accept="image/*">
                            <div class="form-text text-parchment-muted" style="font-size: 11px;">Supported formats: JPG, PNG, WEBP, GIF, SVG (Max 5MB)</div>
                        </div>

                        <!-- URL Fallback -->
                        <div class="col-md-6">
                            <label for="img" class="form-label small text-parchment fw-semibold">
                                <i class="bi bi-link-45deg text-gold me-1"></i> Or Image URL (Unsplash/CDN)
                            </label>
                            <input type="url" name="img" id="img" class="form-control" value="{{ old('img') }}" placeholder="https://images.unsplash.com/photo-...">
                            <div class="form-text text-parchment-muted" style="font-size: 11px;">Used if no file is uploaded above</div>
                        </div>
                    </div>

                    <!-- Live Image Preview Box -->
                    <div id="imagePreviewContainer" class="mt-3 d-none">
                        <label class="form-label small text-gold text-uppercase fw-semibold mb-1">Image Preview</label>
                        <div class="position-relative rounded overflow-hidden border border-gold" style="max-height: 220px; background: #070E07;">
                            <img id="imagePreview" src="" alt="Product Preview" class="w-100 object-fit-cover" style="max-height: 220px;">
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-8">
                <label for="name" class="form-label small text-uppercase fw-semibold text-gold">Product Name *</label>
                <input type="text" name="name" id="name" class="form-control" value="{{ old('name') }}" required placeholder="e.g. Rib-Eye Steak (Dry Aged)">
            </div>

            <div class="col-md-4">
                <label for="sku" class="form-label small text-uppercase fw-semibold text-gold">SKU Code</label>
                <input type="text" name="sku" id="sku" class="form-control" value="{{ old('sku') }}" placeholder="Auto-generated if blank">
            </div>

            <div class="col-md-6">
                <label for="category_id" class="form-label small text-uppercase fw-semibold text-gold">Category *</label>
                <select name="category_id" id="category_id" class="form-select" required>
                    <option value="">Select Category</option>
                    @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-6">
                <label for="subcategory_id" class="form-label small text-uppercase fw-semibold text-gold">Subcategory</label>
                <select name="subcategory_id" id="subcategory_id" class="form-select">
                    <option value="">None / General</option>
                    @foreach($categories as $cat)
                        @foreach($cat->subcategories as $sub)
                        <option value="{{ $sub->id }}" {{ old('subcategory_id') == $sub->id ? 'selected' : '' }}>{{ $cat->name }} → {{ $sub->name }}</option>
                        @endforeach
                    @endforeach
                </select>
            </div>

            <div class="col-md-4">
                <label for="price" class="form-label small text-uppercase fw-semibold text-gold">Price ($) *</label>
                <input type="number" step="0.01" name="price" id="price" class="form-control" value="{{ old('price') }}" required placeholder="19.99">
            </div>

            <div class="col-md-4">
                <label for="stock" class="form-label small text-uppercase fw-semibold text-gold">Stock Quantity *</label>
                <input type="number" name="stock" id="stock" class="form-control" value="{{ old('stock', 15) }}" required>
            </div>

            <div class="col-md-4">
                <label for="badge" class="form-label small text-uppercase fw-semibold text-gold">Badge Tag</label>
                <input type="text" name="badge" id="badge" class="form-control" value="{{ old('badge') }}" placeholder="POPULAR, PREMIUM, FRESH, etc.">
            </div>

            <div class="col-12">
                <label for="desc" class="form-label small text-uppercase fw-semibold text-gold">Product Description</label>
                <textarea name="desc" id="desc" rows="3" class="form-control" placeholder="Describe the cut, origin, butchery style, or flavor profile...">{{ old('desc') }}</textarea>
            </div>

            <div class="col-12 d-flex gap-4 pt-2">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="is_featured" id="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }}>
                    <label class="form-check-label text-parchment" for="is_featured">Show in Featured Selections</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                    <label class="form-check-label text-parchment" for="is_active">Active & Visible in Store</label>
                </div>
            </div>

            <div class="col-12 pt-4 border-top border-secondary border-opacity-25">
                <button type="submit" class="btn btn-gold px-4 py-2">
                    <i class="bi bi-check-circle me-1"></i> Save Product
                </button>
            </div>
        </div>
    </form>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const fileInput = document.getElementById('image_file');
        const urlInput = document.getElementById('img');
        const previewContainer = document.getElementById('imagePreviewContainer');
        const previewImg = document.getElementById('imagePreview');

        fileInput.addEventListener('change', function (e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function (evt) {
                    previewImg.src = evt.target.result;
                    previewContainer.classList.remove('d-none');
                };
                reader.readAsDataURL(file);
            }
        });

        urlInput.addEventListener('input', function () {
            if (!fileInput.files.length && this.value.trim().length > 0) {
                previewImg.src = this.value.trim();
                previewContainer.classList.remove('d-none');
            } else if (!fileInput.files.length && this.value.trim().length === 0) {
                previewContainer.classList.add('d-none');
            }
        });
    });
</script>
@endpush

@endsection

