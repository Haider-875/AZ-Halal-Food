@extends('layouts.app')

@section('title', 'Product Catalog · AZ Halal Marts · Online Orders')

@section('content')

<!-- Hero Section -->
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

<!-- Search & Live Filter Controls -->
<section class="py-4 overflow-hidden">
    <div class="container-xl">

        <div class="row g-3 justify-content-between align-items-center mb-4" data-aos="fade-down" data-aos-duration="700">
            <!-- Search Bar -->
            <div class="col-md-5">
                <div class="position-relative">
                    <i class="bi bi-search position-absolute top-50 start-0 translate-middle-y ms-3 text-gold"></i>
                    <input type="text" id="catalogSearchInput" class="gold-input ps-5" placeholder="Search products, cuts, spices...">
                </div>
            </div>

            <!-- Category Pills -->
            <div class="col-md-7 d-flex flex-wrap justify-content-md-end gap-2">
                @foreach($categories as $cat)
                <button type="button" class="btn-filter catalog-filter-btn {{ $loop->first ? 'active' : '' }}" data-filter="{{ $cat }}">
                    {{ $cat }}
                </button>
                @endforeach
            </div>
        </div>

        <!-- No Results Fallback -->
        <div id="catalogNoResults" class="text-center py-5" style="display: none;">
            <i class="bi bi-search text-gold opacity-50 fs-1"></i>
            <p class="text-parchment-dim mt-3">No products match your search or filter criteria.</p>
        </div>

        <!-- Inventory Grid -->
        <div class="row g-4" id="catalogProductsGrid">
            @foreach($products as $p)
            <div class="col-md-6 col-lg-4 catalog-product-item" data-category="{{ $p->category->name ?? '' }}" data-name="{{ strtolower($p->name) }}" data-desc="{{ strtolower($p->desc) }}" data-aos="fade-up" data-aos-duration="700" data-aos-delay="{{ ($loop->index % 3) * 100 }}">
                <div class="luxury-card h-100 d-flex flex-column">
                    <div class="card-img-wrapper position-relative" style="height: 220px;">
                        <img src="{{ $p->img }}" alt="{{ $p->name }}" class="w-100 h-100 object-fit-cover">
                        <div class="position-absolute top-0 start-0 w-100 h-100" style="background: linear-gradient(to bottom, transparent 40%, rgba(13,23,13,0.9));"></div>

                        {{--
                        <!-- Stock Status Badge (Commented out per request) -->
                        @if($p->stock > 5)
                        <span class="position-absolute top-3 end-3 badge fw-bold" style="background-color: var(--gold); color: #0D170D; font-size: 8px; letter-spacing: 0.25em;">
                            IN STOCK
                        </span>
                        @elseif($p->stock > 0)
                        <span class="position-absolute top-3 end-3 badge fw-bold bg-warning text-dark" style="font-size: 8px; letter-spacing: 0.25em;">
                            LOW · {{ $p->stock }} LEFT
                        </span>
                        @else
                        <span class="position-absolute top-3 end-3 badge fw-bold bg-danger text-white" style="font-size: 8px; letter-spacing: 0.25em;">
                            OUT OF STOCK
                        </span>
                        @endif
                        --}}

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

    </div>
</section>

@endsection
