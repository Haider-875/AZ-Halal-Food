@extends('layouts.app')

@section('title', 'Product Catalog · AZ Halal Marts · Online Orders')

@section('content')

{{-- Hero Section --}}
<section class="position-relative overflow-hidden pt-5 pb-5" style="padding-top: 140px !important;">
    <div class="position-absolute top-0 start-0 w-100 h-100" style="z-index: 1;">
        <img src="https://images.unsplash.com/photo-1542838132-25c8459a5c20?w=1600&q=80" alt="Product catalog" class="w-100 h-100 object-fit-cover" style="filter: brightness(0.2);">
        <div class="position-absolute top-0 start-0 w-100 h-100" style="background: linear-gradient(to bottom, transparent, #0D170D 75%);"></div>
    </div>

    <div class="position-relative text-center px-4 py-5" style="z-index: 2; max-width: 850px; margin: 0 auto;" data-aos="fade-down" data-aos-duration="700">
        <p class="section-label mb-3">Our Offerings</p>
        <h1 class="font-heading font-black text-uppercase text-parchment mb-3" style="font-size: clamp(2.5rem, 6vw, 5rem); line-height: 1.1;">
            Product <span class="text-gold">Catalog</span>
        </h1>
        <p class="text-parchment-dim mx-auto mb-0" style="max-width: 600px; line-height: 1.8;">
            Browse our full selection of halal meats, seafood, groceries, and seasonal items. Fresh cuts prepared daily to order.
        </p>
    </div>
</section>

{{-- Search & Filter Controls --}}
<section class="py-4 overflow-hidden">
    <div class="container-xl">

        <form method="GET" action="{{ route('catalog') }}" id="catalogFilterForm" data-aos="fade-down" data-aos-duration="700">
            {{-- <div class="row g-3 justify-content-between align-items-center mb-4"> --}}
        <div class="row g-3 justify-content-center align-items-center mb-4" data-aos="fade-down" data-aos-duration="700">

                {{-- Search Bar --}}
                <div class="col-md-5">
                    <div class="position-relative">
                        <i class="bi bi-search position-absolute top-50 start-0 translate-middle-y ms-3 text-gold"></i>
                        <input type="text"
                               name="search"
                               id="catalogSearchInput"
                               class="gold-input ps-5"
                               placeholder="Search products, cuts, spices..."
                               value="{{ request('search') }}"
                               autocomplete="off">
                    </div>
                </div>

                {{-- Category Pills --}}
        <div class="d-flex flex-wrap justify-content-center gap-2 mb-5" data-aos="fade-down" data-aos-duration="700">

                {{-- <div class="col-md-12 d-flex flex-wrap justify-content-md-end gap-2"> --}}
                    @foreach($categories as $cat)
                    <button type="submit"
                            name="category"
                            value="{{ $cat }}"
                            class="btn-filter catalog-filter-btn {{ $selectedCategory === $cat ? 'active' : '' }}">
                        {{ $cat }}
                    </button>
                    @endforeach
                </div>
            </div>
        </form>

        {{-- Results Count --}}
        <div class="d-flex align-items-center justify-content-between mb-4">
            <p class="text-parchment-dim small mb-0">
                Showing <span class="text-gold fw-semibold">{{ $products->firstItem() ?? 0 }}–{{ $products->lastItem() ?? 0 }}</span>
                of <span class="text-gold fw-semibold">{{ $products->total() }}</span> products
                @if(request('search')) &mdash; results for <span class="text-gold">"{{ request('search') }}"</span>@endif
                @if($selectedCategory && $selectedCategory !== 'All') in <span class="text-gold">{{ $selectedCategory }}</span>@endif
            </p>
            @if(request('search') || ($selectedCategory && $selectedCategory !== 'All'))
            <a href="{{ route('catalog') }}" class="text-gold small text-decoration-none">
                <i class="bi bi-x-circle me-1"></i>Clear filters
            </a>
            @endif
        </div>

        {{-- No Results --}}
        @if($products->isEmpty())
        <div class="text-center py-5">
            <i class="bi bi-search text-gold opacity-50 fs-1"></i>
            <p class="text-parchment-dim mt-3">No products match your search or filter criteria.</p>
            <a href="{{ route('catalog') }}" class="btn-gold-outline px-4 py-2 mt-2 d-inline-block">Clear Filters</a>
        </div>
        @else

        {{-- Product Grid --}}
        <div class="row g-4" id="catalogProductsGrid">
            @foreach($products as $p)
            <div class="col-md-6 col-lg-4">
                <div class="luxury-card h-100 d-flex flex-column">
                    <div class="card-img-wrapper position-relative" style="height: 220px;">
                        <img src="{{ $p->img }}" alt="{{ $p->name }}" class="w-100 h-100 object-fit-cover" loading="lazy">
                        <div class="position-absolute top-0 start-0 w-100 h-100" style="background: linear-gradient(to bottom, transparent 40%, rgba(13,23,13,0.9));"></div>

                        @if(!empty($p->badge))
                        <span class="position-absolute top-3 end-3 badge text-dark fw-bold" style="background-color: var(--gold); font-size: 8px; letter-spacing: 0.2em;">
                            {{ $p->badge }}
                        </span>
                        @endif

                        <span class="position-absolute top-3 start-3 badge text-gold border" style="border-color: rgba(212,175,55,0.4) !important; background: rgba(13,23,13,0.85); font-size: 8px; letter-spacing: 0.2em;">
                            SKU · {{ $p->sku }}
                        </span>
                    </div>

                    <div class="p-4 d-flex flex-column flex-grow-1">
                        @if(!empty($p->category?->name))
                        <div class="mb-1">
                            <span class="text-gold text-uppercase fw-semibold" style="font-size: 10px; letter-spacing: 0.2em;">
                                {{ $p->category->name }}
                            </span>
                            @if(!empty($p->subcategory?->name))
                            <span class="text-parchment-dim small ms-1" style="font-size: 10px;">· {{ $p->subcategory->name }}</span>
                            @endif
                        </div>
                        @endif
                        <h3 class="font-heading text-uppercase text-gold fw-bold fs-6 mb-2" style="letter-spacing: 0.05em;">{{ $p->name }}</h3>
                        <p class="text-parchment-dim small flex-grow-1 mb-4" style="line-height: 1.7; font-size: 13px;">{{ $p->desc }}</p>

                        <div class="d-flex align-items-center justify-content-between pt-3 border-top" style="border-color: rgba(212,175,55,0.15) !important;">
                            <span class="font-heading fw-bold fs-5 text-parchment">${{ number_format($p->price, 2) }}</span>
                            <div class="card-action-container"
                                data-id="{{ $p->id }}"
                                data-name="{{ $p->name }}"
                                data-price="{{ $p->price }}"
                                data-sku="{{ $p->sku }}"
                                data-img="{{ $p->img }}">
                                <button type="button" class="btn-gold-outline py-2 px-3 btn-add-to-cart"
                                    data-id="{{ $p->id }}"
                                    data-name="{{ $p->name }}"
                                    data-price="{{ $p->price }}"
                                    data-sku="{{ $p->sku }}"
                                    data-img="{{ $p->img }}"
                                    style="font-size: 10px; letter-spacing: 0.2em;">
                                    <span>Add to Cart</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        {{-- Pagination --}}
        @if($products->hasPages())
        <div class="d-flex justify-content-center mt-5 pt-3" data-aos="fade-up">
            <nav aria-label="Product catalog pagination">
                {{-- Custom styled pagination --}}
                <ul class="pagination catalog-pagination mb-0">
                    {{-- Previous --}}
                    @if($products->onFirstPage())
                    <li class="page-item disabled">
                        <span class="page-link"><i class="bi bi-chevron-left"></i></span>
                    </li>
                    @else
                    <li class="page-item">
                        <a class="page-link" href="{{ $products->previousPageUrl() }}"><i class="bi bi-chevron-left"></i></a>
                    </li>
                    @endif

                    {{-- Page numbers --}}
                    @foreach($products->getUrlRange(1, $products->lastPage()) as $page => $url)
                    @if($page == $products->currentPage())
                    <li class="page-item active" aria-current="page">
                        <span class="page-link">{{ $page }}</span>
                    </li>
                    @elseif($page == 1 || $page == $products->lastPage() || abs($page - $products->currentPage()) <= 2)
                    <li class="page-item">
                        <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                    </li>
                    @elseif(abs($page - $products->currentPage()) == 3)
                    <li class="page-item disabled"><span class="page-link">&hellip;</span></li>
                    @endif
                    @endforeach

                    {{-- Next --}}
                    @if($products->hasMorePages())
                    <li class="page-item">
                        <a class="page-link" href="{{ $products->nextPageUrl() }}"><i class="bi bi-chevron-right"></i></a>
                    </li>
                    @else
                    <li class="page-item disabled">
                        <span class="page-link"><i class="bi bi-chevron-right"></i></span>
                    </li>
                    @endif
                </ul>
            </nav>
        </div>
        @endif

        @endif {{-- end products not empty --}}

    </div>
</section>

{{-- Pagination styles --}}
@push('styles')
<style>
.catalog-pagination .page-link {
    background: transparent;
    border: 1px solid rgba(212,175,55,0.25);
    color: var(--gold);
    padding: 0.5rem 0.85rem;
    font-size: 0.875rem;
    letter-spacing: 0.05em;
    transition: all 0.2s ease;
    margin: 0 2px;
    border-radius: 4px !important;
}
.catalog-pagination .page-link:hover {
    background: rgba(212,175,55,0.12);
    border-color: var(--gold);
    color: var(--gold);
}
.catalog-pagination .page-item.active .page-link {
    background: var(--gold);
    border-color: var(--gold);
    color: #0D170D;
    font-weight: 700;
}
.catalog-pagination .page-item.disabled .page-link {
    background: transparent;
    border-color: rgba(212,175,55,0.1);
    color: rgba(212,175,55,0.3);
    cursor: not-allowed;
}
</style>
@endpush

{{-- Submit form on search input with debounce --}}
@push('scripts')
<script>
(function () {
    const searchInput = document.getElementById('catalogSearchInput');
    if (!searchInput) return;

    let debounceTimer;
    searchInput.addEventListener('input', function () {
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(function () {
            document.getElementById('catalogFilterForm').submit();
        }, 500);
    });
})();
</script>
@endpush

@endsection
