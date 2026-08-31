@extends('admin.layouts.admin')

@section('title', 'Edit Hero Slide · Admin Portal')
@section('page_title', 'Edit Hero Slide')

@section('content')

<div class="admin-card p-4 mx-auto" style="max-width: 900px;">
    <div class="d-flex justify-content-between align-items-center mb-4 pb-3 border-bottom border-secondary border-opacity-25">
        <div>
            <h5 class="font-heading text-gold mb-1 fw-bold">Edit Slide #{{ $slider->id }}</h5>
            <p class="text-parchment-muted small mb-0">Update slide imagery, messaging, active visibility, or button destinations.</p>
        </div>
        <a href="{{ route('admin.sliders.index') }}" class="btn-outline-gold btn-sm">
            <i class="bi bi-arrow-left me-1"></i> Back to Sliders
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

    <form action="{{ route('admin.sliders.update', $slider) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="row g-3">
            <!-- Image Upload Options -->
            <div class="col-12">
                <div class="p-3 rounded border border-secondary border-opacity-25 mb-3" style="background: rgba(13, 23, 13, 0.5);">
                    <label class="form-label text-uppercase fw-semibold text-gold small d-flex align-items-center gap-2 mb-2">
                        <i class="bi bi-image"></i> Slide Banner Image
                    </label>

                    <!-- Current Active Image -->
                    <div class="mb-3">
                        <label class="form-label small text-parchment-dim mb-1">Current Image</label>
                        <div class="position-relative rounded overflow-hidden border border-gold" style="max-height: 200px; max-width: 450px; background: #070E07;">
                            <img id="currentImage" src="{{ $slider->image_url }}" alt="{{ $slider->title }}" class="w-100 object-fit-cover" style="max-height: 200px;">
                        </div>
                    </div>

                    <div class="row g-3">
                        <!-- Option A: File Upload Replacement -->
                        <div class="col-md-6">
                            <label for="image_file" class="form-label small text-parchment fw-semibold">
                                <i class="bi bi-cloud-arrow-up text-gold me-1"></i> Upload New Image File
                            </label>
                            <input type="file" name="image_file" id="image_file" class="form-control" accept="image/*">
                            <div class="form-text text-parchment-muted" style="font-size: 11px;">Leave empty to keep existing image</div>
                        </div>

                        <!-- Option B: External URL Replacement -->
                        <div class="col-md-6">
                            <label for="image_url" class="form-label small text-parchment fw-semibold">
                                <i class="bi bi-link-45deg text-gold me-1"></i> Or Update Image URL
                            </label>
                            <input type="url" name="image_url" id="image_url" class="form-control" value="{{ old('image_url', str_starts_with($slider->image, 'http') ? $slider->image : '') }}" placeholder="https://images.unsplash.com/photo-...">
                            <div class="form-text text-parchment-muted" style="font-size: 11px;">Leave empty to keep existing file/URL</div>
                        </div>
                    </div>

                    <!-- Live Image Preview Box for New Selected Image -->
                    <div id="imagePreviewContainer" class="mt-3 d-none">
                        <label class="form-label small text-gold text-uppercase fw-semibold mb-1">New Image Preview</label>
                        <div class="position-relative rounded overflow-hidden border border-success" style="max-height: 200px; max-width: 450px; background: #070E07;">
                            <img id="imagePreview" src="" alt="New Preview" class="w-100 object-fit-cover" style="max-height: 200px;">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Slide Messaging -->
            <div class="col-md-8">
                <label for="title" class="form-label small text-uppercase fw-semibold text-gold">Slide Headline / Title *</label>
                <input type="text" name="title" id="title" class="form-control" value="{{ old('title', $slider->title) }}" required placeholder="e.g. Halal. The Art of Flavor.">
            </div>

            <div class="col-md-4">
                <label for="badge" class="form-label small text-uppercase fw-semibold text-gold">Badge Tag (Optional)</label>
                <input type="text" name="badge" id="badge" class="form-control" value="{{ old('badge', $slider->badge) }}" placeholder="e.g. 100% ZABIHA, FRESH ARRIVAL">
            </div>

            <div class="col-12">
                <label for="subtitle" class="form-label small text-uppercase fw-semibold text-gold">Subtitle / Tagline (Optional)</label>
                <input type="text" name="subtitle" id="subtitle" class="form-control" value="{{ old('subtitle', $slider->subtitle) }}" placeholder="e.g. ✦ Cary, North Carolina · Premium Halal Meats & Groceries ✦">
            </div>

            <div class="col-12">
                <label for="description" class="form-label small text-uppercase fw-semibold text-gold">Slide Description (Optional)</label>
                <textarea name="description" id="description" rows="2" class="form-control" placeholder="Short description or promo highlight...">{{ old('description', $slider->description) }}</textarea>
            </div>

            <!-- Action Buttons -->
            <div class="col-md-6">
                <div class="p-3 rounded border border-secondary border-opacity-25" style="background: rgba(13, 23, 13, 0.3);">
                    <label class="form-label small text-uppercase fw-semibold text-gold mb-2">Primary Button</label>
                    <div class="mb-2">
                        <label for="button_text" class="form-label small text-parchment-dim">Button Label</label>
                        <input type="text" name="button_text" id="button_text" class="form-control" value="{{ old('button_text', $slider->button_text) }}" placeholder="e.g. Explore Offerings">
                    </div>
                    <div>
                        <label for="button_link" class="form-label small text-parchment-dim">Target Link / URL</label>
                        <input type="text" name="button_link" id="button_link" class="form-control" value="{{ old('button_link', $slider->button_link) }}" placeholder="e.g. /products">
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="p-3 rounded border border-secondary border-opacity-25" style="background: rgba(13, 23, 13, 0.3);">
                    <label class="form-label small text-uppercase fw-semibold text-gold mb-2">Secondary Button (Optional)</label>
                    <div class="mb-2">
                        <label for="secondary_button_text" class="form-label small text-parchment-dim">Button Label</label>
                        <input type="text" name="secondary_button_text" id="secondary_button_text" class="form-control" value="{{ old('secondary_button_text', $slider->secondary_button_text) }}" placeholder="e.g. Shop Catalog">
                    </div>
                    <div>
                        <label for="secondary_button_link" class="form-label small text-parchment-dim">Target Link / URL</label>
                        <input type="text" name="secondary_button_link" id="secondary_button_link" class="form-control" value="{{ old('secondary_button_link', $slider->secondary_button_link) }}" placeholder="e.g. /catalog">
                    </div>
                </div>
            </div>

            <!-- Display Order & Active State -->
            <div class="col-md-4">
                <label for="display_order" class="form-label small text-uppercase fw-semibold text-gold">Display Order</label>
                <input type="number" name="display_order" id="display_order" class="form-control" value="{{ old('display_order', $slider->display_order) }}" placeholder="0">
                <div class="form-text text-parchment-muted" style="font-size: 11px;">Lower numbers appear first (0, 1, 2...)</div>
            </div>

            <div class="col-md-8 d-flex align-items-center pt-md-4">
                <div class="form-check mt-2">
                    <input class="form-check-input" type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $slider->is_active) ? 'checked' : '' }}>
                    <label class="form-check-label text-parchment fw-semibold" for="is_active">
                        Active & Visible on Frontend Slides
                    </label>
                </div>
            </div>

            <!-- Form Submit -->
            <div class="col-12 pt-4 border-top border-secondary border-opacity-25 d-flex gap-3">
                <button type="submit" class="btn btn-gold px-4 py-2">
                    <i class="bi bi-check-lg me-1"></i> Update Hero Slide
                </button>
                <a href="{{ route('admin.sliders.index') }}" class="btn btn-outline-secondary px-3 py-2">
                    Cancel
                </a>
            </div>
        </div>
    </form>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const fileInput = document.getElementById('image_file');
        const urlInput = document.getElementById('image_url');
        const previewContainer = document.getElementById('imagePreviewContainer');
        const previewImg = document.getElementById('imagePreview');

        fileInput.addEventListener('change', function () {
            if (this.files && this.files[0]) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    previewImg.src = e.target.result;
                    previewContainer.classList.remove('d-none');
                };
                reader.readAsDataURL(this.files[0]);
            }
        });

        urlInput.addEventListener('input', function () {
            if (!fileInput.files || !fileInput.files.length) {
                if (this.value.trim().length > 5) {
                    previewImg.src = this.value.trim();
                    previewContainer.classList.remove('d-none');
                } else {
                    previewContainer.classList.add('d-none');
                }
            }
        });
    });
</script>
@endpush

@endsection

