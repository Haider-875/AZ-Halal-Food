@extends('admin.layouts.admin')

@section('title', 'Gallery Archive · Admin Portal')
@section('page_title', 'Visual Archive Gallery')

@section('content')

<div class="row g-4">
    <!-- Gallery Grid List -->
    <div class="col-lg-8">
        <div class="admin-card p-3">
            <h6 class="font-heading text-gold mb-3 fw-bold"><i class="bi bi-images me-2"></i> Current Gallery Items ({{ $items->count() }})</h6>

            <div class="row g-3">
                @foreach($items as $item)
                <div class="col-md-6 col-xl-4">
                    <div class="border border-secondary border-opacity-25 rounded overflow-hidden bg-dark">
                        <img src="{{ $item->src }}" alt="{{ $item->caption }}" style="width: 100%; height: 140px; object-fit: cover;">
                        <div class="p-2 d-flex justify-content-between align-items-center">
                            <div>
                                <strong class="small text-parchment text-truncate d-block" style="max-width: 120px;">{{ $item->caption }}</strong>
                                <span class="badge bg-gold text-dark" style="font-size: 8px;">{{ $item->tag }}</span>
                            </div>
                            <form action="{{ route('admin.gallery.destroy', $item) }}" method="POST" onsubmit="return confirm('Delete this gallery image?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger py-0 px-1"><i class="bi bi-trash"></i></button>
                            </form>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Add Gallery Form -->
    <div class="col-lg-4">
        <div class="admin-card p-3">
            <h6 class="font-heading text-gold mb-3 fw-bold"><i class="bi bi-cloud-arrow-up me-2"></i> Add Gallery Image</h6>

            <form action="{{ route('admin.gallery.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="src" class="form-label small text-uppercase text-gold">Image URL *</label>
                    <input type="url" name="src" id="src" class="form-control" required placeholder="https://images.unsplash.com/...">
                </div>

                <div class="mb-3">
                    <label for="caption" class="form-label small text-uppercase text-gold">Caption / Title *</label>
                    <input type="text" name="caption" id="caption" class="form-control" required placeholder="e.g. Prime Dry-Aged Rib-Eye">
                </div>

                <div class="mb-3">
                    <label for="tag" class="form-label small text-uppercase text-gold">Category Tag *</label>
                    <select name="tag" id="tag" class="form-select" required>
                        <option value="BEEF">BEEF</option>
                        <option value="GOAT">GOAT</option>
                        <option value="LAMB">LAMB</option>
                        <option value="SEAFOOD">SEAFOOD</option>
                        <option value="MANGO">MANGO</option>
                        <option value="GROCERY">GROCERY</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="size" class="form-label small text-uppercase text-gold">Tile Layout Size</label>
                    <select name="size" id="size" class="form-select">
                        <option value="normal">Normal</option>
                        <option value="tall">Tall</option>
                        <option value="wide">Wide</option>
                    </select>
                </div>

                <button type="submit" class="btn btn-gold w-100">Upload to Gallery</button>
            </form>
        </div>
    </div>
</div>

@endsection
