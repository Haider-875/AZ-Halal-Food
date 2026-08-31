@extends('admin.layouts.admin')

@section('title', 'Add New Hero Slide · Admin Portal')
@section('page_title', 'Create Hero Slide')

@section('content')

<div class="admin-card p-4 mx-auto" style="max-width: 900px;">
    <div class="d-flex justify-content-between align-items-center mb-4 pb-3 border-bottom border-secondary border-opacity-25">
        <div>
            <h5 class="font-heading text-gold mb-1 fw-bold">New Frontend Hero Slide</h5>
            <p class="text-parchment-muted small mb-0">Upload a banner image from your device or provide a web URL, and configure headline messaging.</p>
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

    <form action="{{ route('admin.sliders.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="row g-3">
            <!-- Image Upload Options -->
            <div class="col-12">
                <div class="p-3 rounded border border-secondary border-opacity-25 mb-3" style="background: rgba(13, 23, 13, 0.5);">
                    <label class="form-label text-uppercase fw-semibold text-gold small d-flex align-items-center gap-2 mb-2">
                        <i class="bi bi-image"></i> Slide Banner Image *
                    </label>
                    <p class="text-parchment-muted small mb-3">
                        Choose how to supply the slide image. You can upload a high-resolution photo (JPG, PNG, WEBP up to 5MB) directly from your computer or supply an external CDN/Unsplash URL. Recommended aspect ratio: 16:9 or 21:9 (e.g., 1920x1080px).
                    </p>

                    <div class="row g-3">
                        <!-- Option A: File Upload -->
                        <div class="col-md-6">
                            <label for="image_file" class="form-label small text-parchment fw-semibold">
                                <i class="bi bi-cloud-arrow-up text-gold me-1"></i> Upload Image File
                            </label>
                            <input type="file" name="image_file" id="image_file" class="form-control" accept="image/*">
                            <div class="form-text text-parchment-muted" style="font-size: 11px;">Supported formats: JPG, PNG, WEBP, GIF, SVG (Max 5MB)</div>
                        </div>

                        <!-- Option B: External URL -->
                        <div class="col-md-6">
                            <label for="image_url" class="form-label small text-parchment fw-semibold">
                                <i class="bi bi-link-45deg text-gold me-1"></i> Or Image Web URL
                            </label>
                            <input type="url" name="image_url" id="image_url" class="form-control" value="{{ old('image_url') }}" placeholder="https://images.unsplash.com/photo-...">
                            <div class="form-text text-parchment-muted" style="font-size: 11px;">Used if no file is uploaded above</div>
                        </div>
                    </div>

                    <!-- Live Image Preview Box -->
                    <div id="imagePreviewContainer" class="mt-3 d-none">
                        <label class="form-label small text-gold text-uppercase fw-semibold mb-1">Image Preview</label>
                        <div class="position-relative rounded overflow-hidden border border-gold" style="max-height: 220px; background: #070E07;">
                            <img id="imagePreview" src="" alt="Slide Preview" class="w-100 object-fit-cover" style="max-height: 220px;">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Slide Messaging -->
            <div class="col-md-8">
                <label for="title" class="form-label small text-uppercase fw-semibold text-gold">Slide Headline / Title *</label>
                <input type="text" name="title" id="title" class="form-control" value="{{ old('title') }}" required placeholder="e.g. Halal. The Art of Flavor.">
            </div>

            <div class="col-md-4">
                <label for="badge" class="form-label small text-uppercase fw-semibold text-gold">Badge Tag (Optional)</label>
                <input type="text" name="badge" id="badge" class="form-control" value="{{ old('badge') }}" placeholder="e.g. 100% ZABIHA, FRESH ARRIVAL">
            </div>

            <div class="col-12">
                <label for="subtitle" class="form-label small text-uppercase fw-semibold text-gold">Subtitle / Tagline (Optional)</label>
                <input type="text" name="subtitle" id="subtitle" class="form-control" value="{{ old('subtitle') }}" placeholder="e.g. ✦ Cary, North Carolina · Premium Halal Meats & Groceries ✦">
            </div>

            <div class="col-12">
                <label for="description" class="form-label small text-uppercase fw-semibold text-gold">Slide Description (Optional)</label>
                <textarea name="description" id="description" rows="2" class="form-control" placeholder="Short description or promo highlight...">{{ old('description') }}</textarea>
            </div>

            <!-- Action Buttons -->
            <div class="col-md-6">
                <div class="p-3 rounded border border-secondary border-opacity-25" style="background: rgba(13, 23, 13, 0.3);">
                    <label class="form-label small text-uppercase fw-semibold text-gold mb-2">Primary Button</label>
                    <div class="mb-2">
                        <label for="button_text" class="form-label small text-parchment-dim">Button Label</label>
                        <input type="text" name="button_text" id="button_text" class="form-control" value="{{ old('button_text', 'Explore Our Offerings') }}" placeholder="e.g. Explore Offerings">
                    </div>
                    <div>
                        <label for="button_link" class="form-label small text-parchment-dim">Target Link / URL</label>
                        <input type="text" name="button_link" id="button_link" class="form-control" value="{{ old('button_link', '/products') }}" placeholder="e.g. /products">
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="p-3 rounded border border-secondary border-opacity-25" style="background: rgba(13, 23, 13, 0.3);">
                    <label class="form-label small text-uppercase fw-semibold text-gold mb-2">Secondary Button (Optional)</label>
                    <div class="mb-2">
                        <label for="secondary_button_text" class="form-label small text-parchment-dim">Button Label</label>
                        <input type="text" name="secondary_button_text" id="secondary_button_text" class="form-control" value="{{ old('secondary_button_text', 'Shop Catalog') }}" placeholder="e.g. Shop Catalog">
                    </div>
                    <div>
                        <label for="secondary_button_link" class="form-label small text-parchment-dim">Target Link / URL</label>
                        <input type="text" name="secondary_button_link" id="secondary_button_link" class="form-control" value="{{ old('secondary_button_link', '/catalog') }}" placeholder="e.g. /catalog">
                    </div>
                </div>
            </div>

            <!-- Display Order & Active State -->
            <div class="col-md-4">
                <label for="display_order" class="form-label small text-uppercase fw-semibold text-gold">Display Order</label>
                <input type="number" name="display_order" id="display_order" class="form-control" value="{{ old('display_order', 0) }}" placeholder="0">
                <div class="form-text text-parchment-muted" style="font-size: 11px;">Lower numbers appear first (0, 1, 2...)</div>
            </div>

            <div class="col-md-8 d-flex align-items-center pt-md-4">
                <div class="form-check mt-2">
                    <input class="form-check-input" type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                    <label class="form-check-label text-parchment fw-semibold" for="is_active">
                        Active & Visible on Frontend Slides
                    </label>
                </div>
            </div>

            <!-- Form Submit -->
            <div class="col-12 pt-4 border-top border-secondary border-opacity-25 d-flex gap-3">
                <button type="submit" class="btn btn-gold px-4 py-2">
                    <i class="bi bi-check-lg me-1"></i> Save Hero Slide
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

