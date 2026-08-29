@extends('admin.layouts.admin')

@section('title', 'Categories & Subcategories · Admin Portal')
@section('page_title', 'Categories & Subcategories')

@section('content')

<div class="row g-4">
    <!-- Category List & Subcategories -->
    <div class="col-lg-8">
        <div class="admin-card">
            <div class="p-3 border-bottom border-secondary border-opacity-25">
                <h6 class="font-heading text-gold mb-0 fw-bold"><i class="bi bi-tags me-2"></i> All Categories</h6>
            </div>

            <div class="p-3 d-flex flex-column gap-3">
                @foreach($categories as $cat)
                <div class="p-3 border border-secondary border-opacity-25 rounded bg-dark bg-opacity-50">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <div>
                            <strong class="font-heading fs-5 text-gold">{{ $cat->name }}</strong>
                            <span class="badge bg-secondary ms-2">{{ $cat->products_count }} products</span>
                        </div>
                        <div class="d-flex gap-2">
                            <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="collapse" data-bs-target="#subCatCollapse{{ $cat->id }}">
                                <i class="bi bi-chevron-down"></i> Subcategories ({{ $cat->subcategories->count() }})
                            </button>
                            <form action="{{ route('admin.categories.destroy', $cat) }}" method="POST" onsubmit="return confirm('Delete this category and its subcategories?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                            </form>
                        </div>
                    </div>
                    <p class="text-parchment-dim small mb-2">{{ $cat->description }}</p>

                    <!-- Subcategories Accordion -->
                    <div class="collapse mt-3 pt-3 border-top border-secondary border-opacity-25" id="subCatCollapse{{ $cat->id }}">
                        <h6 class="small text-uppercase text-gold-light fw-bold mb-2">Subcategories:</h6>
                        <div class="d-flex flex-wrap gap-2 mb-3">
                            @forelse($cat->subcategories as $sub)
                            <span class="badge bg-dark border border-secondary text-parchment p-2 d-flex align-items-center gap-2">
                                {{ $sub->name }}
                                <form action="{{ route('admin.categories.subcategories.destroy', $sub) }}" method="POST" class="d-inline" onsubmit="return confirm('Remove subcategory?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-link p-0 text-danger" style="line-height:1;"><i class="bi bi-x"></i></button>
                                </form>
                            </span>
                            @empty
                            <span class="text-parchment-muted small">No subcategories yet.</span>
                            @endforelse
                        </div>

                        <!-- Add Subcategory Form -->
                        <form action="{{ route('admin.categories.subcategories.store') }}" method="POST" class="row g-2">
                            @csrf
                            <input type="hidden" name="category_id" value="{{ $cat->id }}">
                            <div class="col-sm-8">
                                <input type="text" name="name" class="form-control form-control-sm" placeholder="New subcategory name..." required>
                            </div>
                            <div class="col-sm-4">
                                <button type="submit" class="btn btn-outline-gold btn-sm w-100">+ Add Sub</button>
                            </div>
                        </form>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Create Category Sidebar -->
    <div class="col-lg-4">
        <div class="admin-card p-3">
            <h6 class="font-heading text-gold mb-3 fw-bold"><i class="bi bi-plus-circle me-2"></i> Create New Category</h6>
            
            <form action="{{ route('admin.categories.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="cat_name" class="form-label small text-uppercase text-gold">Category Name *</label>
                    <input type="text" name="name" id="cat_name" class="form-control" required placeholder="e.g. Poultry & Chicken">
                </div>

                <div class="mb-3">
                    <label for="cat_desc" class="form-label small text-uppercase text-gold">Description</label>
                    <textarea name="description" id="cat_desc" rows="3" class="form-control" placeholder="Short summary for store display..."></textarea>
                </div>

                <div class="mb-3">
                    <label for="cat_order" class="form-label small text-uppercase text-gold">Display Order</label>
                    <input type="number" name="display_order" id="cat_order" class="form-control" value="0">
                </div>

                <button type="submit" class="btn btn-gold w-100">Create Category</button>
            </form>
        </div>
    </div>
</div>

@endsection
