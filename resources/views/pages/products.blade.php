@extends('layouts.app')

@section('title', 'Products & Pricing · AZ Halal Marts · Hand-Cut Halal Meats & Seafood')

@section('content')

<!-- Hero Section -->
<section class="position-relative overflow-hidden pt-5 pb-5" style="padding-top: 140px !important;">
    <div class="position-absolute top-0 start-0 w-100 h-100" style="z-index: 1;">
        <img src="https://images.unsplash.com/photo-1553163147-622ab57be1c7?w=1600&q=80" alt="Halal meat selection" class="w-100 h-100 object-fit-cover" style="filter: brightness(0.2);">
        <div class="position-absolute top-0 start-0 w-100 h-100" style="background: linear-gradient(to bottom, transparent, #0D170D 75%);"></div>
    </div>

    <div class="position-relative text-center px-4 py-5" style="z-index: 2; max-width: 850px; margin: 0 auto;" data-aos="fade-down" data-aos-duration="700">
        <p class="section-label mb-3">What We Offer</p>
        <h1 class="font-heading font-black text-uppercase text-parchment mb-3" style="font-size: clamp(2.5rem, 6vw, 5rem); line-height: 1.1;">
            Our <span class="text-gold">Products</span> & Pricing
        </h1>
        <p class="text-parchment-dim mx-auto mb-4" style="max-width: 600px; line-height: 1.8;">
            All meats are freshly slaughtered, Zabiha halal certified, and priced competitively. Call or WhatsApp for current market pricing and custom cutting specifications.
        </p>
        <div class="d-flex justify-content-center gap-3">
            <a href="{{ route('catalog') }}" class="text-gold text-uppercase fw-semibold small text-decoration-none d-flex align-items-center gap-2" style="letter-spacing: 0.25em; font-size: 11px;">
                View Live Inventory Catalog <i class="bi bi-arrow-right"></i>
            </a>
        </div>
    </div>
</section>

<!-- Products Grid Section -->
<section class="py-5 overflow-hidden">
    <div class="container-xl">

        <!-- Search Bar & Controls -->
        <div class="row g-3 justify-content-center align-items-center mb-4" data-aos="fade-down" data-aos-duration="700">
            <div class="col-md-6 col-lg-5">
                <form action="{{ route('products') }}" method="GET" class="position-relative">
                    @if(request('category') && strtolower(request('category')) !== 'all')
                    <input type="hidden" name="category" value="{{ request('category') }}">
                    @endif
                    <i class="bi bi-search position-absolute top-50 start-0 translate-middle-y ms-3 text-gold"></i>
                    <input type="text" name="search" class="gold-input ps-5 pe-5 w-100" placeholder="Search meats, seafood, groceries..." value="{{ request('search') }}">
                    @if(request('search'))
                    <a href="{{ request('category') ? route('products', ['category' => request('category')]) : route('products') }}" class="position-absolute top-50 end-0 translate-middle-y me-3 text-parchment-dim hover-gold" title="Clear search">
                        <i class="bi bi-x-circle"></i>
                    </a>
                    @endif
                </form>
            </div>
        </div>

        <!-- Category Filter Tabs -->
        <div class="d-flex flex-wrap justify-content-center gap-2 mb-5" data-aos="fade-down" data-aos-duration="700">
            @foreach($categories as $cat)
            @php
                $isActive = (strtolower($selectedCategory ?? 'All') === strtolower($cat)) || (empty($selectedCategory) && $cat === 'All');
                $catUrl = ($cat === 'All')
                    ? (request('search') ? route('products', ['search' => request('search')]) : route('products'))
                    : route('products', array_filter(['category' => $cat, 'search' => request('search')]));
            @endphp
            <a href="{{ $catUrl }}" class="btn-filter {{ $isActive ? 'active' : '' }} text-decoration-none">
                {{ $cat }}
            </a>
            @endforeach
        </div>

        @if($products->isEmpty())
        <!-- No Results Fallback -->
        <div id="catalogNoResults" class="text-center py-5">
            <i class="bi bi-search text-gold opacity-50 fs-1"></i>
            <p class="text-parchment-dim mt-3">No products match your filter or search criteria.</p>
            @if(request('category') || request('search'))
            <div class="mt-3">
                <a href="{{ route('products') }}" class="btn-gold-outline d-inline-block">
                    <span>View All Products</span>
                </a>
            </div>
            @endif
        </div>
        @else
        <!-- Products Grid -->
        <div class="row g-4" id="productsGridContainer">
            @foreach($products as $p)
            <div class="col-md-6 col-lg-4 catalog-product-item" data-category="{{ $p->category->name ?? ($p['category']['name'] ?? ($p['category'] ?? '')) }}" data-name="{{ strtolower($p['name'] ?? $p->name) }}" data-desc="{{ strtolower($p['desc'] ?? $p->desc) }}">
                <div class="luxury-card h-100 d-flex flex-column">
                    <div class="card-img-wrapper position-relative" style="height: 230px;">
                        <img src="{{ $p['img'] }}" alt="{{ $p['name'] }}" class="w-100 h-100 object-fit-cover" loading="lazy">
                        <div class="position-absolute top-0 start-0 w-100 h-100" style="background: linear-gradient(to bottom, transparent 40%, rgba(13,23,13,0.9));"></div>

                        @if(!empty($p['badge']))
                        <span class="position-absolute top-3 end-3 badge text-dark fw-bold" style="background-color: var(--gold); font-size: 8px; letter-spacing: 0.2em;">
                            {{ $p['badge'] }}
                        </span>
                        @endif

                        <span class="position-absolute top-3 start-3 badge text-gold border" style="border-color: rgba(212,175,55,0.4) !important; background: rgba(13,23,13,0.85); font-size: 8px; letter-spacing: 0.2em;">
                            SKU · {{ $p['sku'] }}
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
                        <h3 class="font-heading text-uppercase text-gold fw-bold fs-6 mb-2" style="letter-spacing: 0.05em;">{{ $p['name'] }}</h3>
                        <p class="text-parchment-dim small flex-grow-1 mb-4" style="line-height: 1.7; font-size: 13px;">{{ $p['desc'] }}</p>

                        <div class="d-flex align-items-center justify-content-between pt-3 border-top" style="border-color: rgba(212,175,55,0.15) !important;">
                            <span class="font-heading fw-bold fs-5 text-parchment">${{ number_format($p['price'], 2) }}</span>
                            <div class="card-action-container"
                                data-id="{{ $p['id'] }}"
                                data-name="{{ $p['name'] }}"
                                data-price="{{ $p['price'] }}"
                                data-sku="{{ $p['sku'] }}"
                                data-img="{{ $p['img'] }}">
                                <button type="button" class="btn-gold-outline py-2 px-3 btn-add-to-cart"
                                    data-id="{{ $p['id'] }}"
                                    data-name="{{ $p['name'] }}"
                                    data-price="{{ $p['price'] }}"
                                    data-sku="{{ $p['sku'] }}"
                                    data-img="{{ $p['img'] }}"
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

        @if($products->hasPages())
        <!-- Pagination Links -->
        <div class="pagination-wrapper mt-5 d-flex justify-content-center">
            {{ $products->links('pagination::bootstrap-5') }}
        </div>
        @endif
        @endif


    </div>
</section>

<!-- Pricing Policy Card Section -->
<section class="py-5 overflow-hidden" style="background-color: var(--obsidian-surface); border-top: 1px solid var(--border-gold);">
    <div class="container-xl py-4">
        <div class="p-4 p-md-5 mx-auto text-center" style="max-width: 800px; background-color: var(--obsidian-card); border: 1px solid var(--border-gold);" data-aos="zoom-in" data-aos-duration="750">
            <p class="section-label mb-3">Pricing Policy</p>
            <h2 class="font-heading font-black text-uppercase text-parchment mb-3" style="font-size: clamp(1.8rem, 3vw, 2.5rem);">
                Transparent & Fair Pricing
            </h2>
            <p class="text-parchment-dim mb-4" style="line-height: 1.8; font-size: 14px;">
                Meat prices fluctuate with market conditions. We always offer competitive, fair pricing and never upcharge unfairly. Check our latest updates or reach out directly.
            </p>
            <div class="d-flex flex-column flex-sm-row gap-3 justify-content-center">
                <a href="tel:9192448634" class="btn-gold-outline">
                    <span>📞 Call for Pricing</span>
                </a>
                <a href="https://wa.me/19192448634" target="_blank" rel="noopener noreferrer" class="btn-gold-outline">
                    <span>💬 WhatsApp</span>
                </a>
            </div>
        </div>
    </div>
</section>

@endsection
