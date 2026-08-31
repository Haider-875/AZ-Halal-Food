@extends('layouts.app')

@section('title', 'AZ Halal Marts · Premium Halal Meats, Seafood & Groceries · Cary, NC')

@section('content')

@if(isset($sliders) && $sliders->isNotEmpty())
<!-- Hero Carousel Slider -->
<section class="position-relative overflow-hidden" style="min-height: 90vh;" data-aos="fade">
    <div id="heroCarousel" class="carousel slide carousel-fade h-100" data-bs-ride="carousel" data-bs-interval="6000">
        @if($sliders->count() > 1)
        <div class="carousel-indicators mb-4" style="z-index: 10;" data-aos="zoom-in-up" data-aos-delay="300">
            @foreach($sliders as $idx => $slide)
            <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="{{ $idx }}" class="{{ $idx === 0 ? 'active' : '' }}" aria-current="{{ $idx === 0 ? 'true' : 'false' }}" aria-label="Slide {{ $idx + 1 }}"></button>
            @endforeach
        </div>
        @endif

        <div class="carousel-inner h-100">
            @foreach($sliders as $idx => $slide)
            <div class="carousel-item {{ $idx === 0 ? 'active' : '' }} position-relative" style="min-height: 90vh;">
                <!-- Slide Background Image & Overlay -->
                <div class="position-absolute top-0 start-0 w-100 h-100" style="z-index: 1;">
                    <img src="{{ $slide->image_url }}" alt="{{ $slide->title }}" class="w-100 h-100 object-fit-cover" style="filter: brightness(0.28) saturate(0.9);">
                    <div class="position-absolute top-0 start-0 w-100 h-100" style="background: linear-gradient(135deg, rgba(13,23,13,0.75) 0%, rgba(13,23,13,0.3) 50%, rgba(13,23,13,0.85) 100%);"></div>
                    <div class="position-absolute m-3 m-md-5 pointer-events-none" style="top:0; bottom:0; left:0; right:0; border: 1px solid rgba(212,175,55,0.2);"></div>
                </div>

                <!-- Slide Content -->
                <div class="position-relative d-flex align-items-center justify-content-center text-center px-4 py-5" style="z-index: 2; min-height: 90vh;">
                    <div style="max-width: 900px;">
                        @if($slide->badge)
                        <div class="mb-3" data-aos="zoom-in-down" data-aos-delay="100">
                            <span class="badge text-gold border px-3 py-2" style="border-color: rgba(212,175,55,0.5) !important; background: rgba(13,23,13,0.85); font-size: 10px; letter-spacing: 0.3em;">
                                {{ $slide->badge }}
                            </span>
                        </div>
                        @else
                        <p class="section-label mb-3" style="letter-spacing: 0.4em;" data-aos="fade-down" data-aos-delay="100">✦ Cary, North Carolina ✦</p>
                        @endif

                        <h1 class="font-heading font-black text-uppercase text-gold mb-3" style="font-size: clamp(2.5rem, 6.5vw, 5.5rem); line-height: 1.1; letter-spacing: 0.02em; text-shadow: 0 0 70px rgba(212,175,55,0.3);" data-aos="fade-up" data-aos-duration="800">
                            {{ $slide->title }}
                        </h1>

                        @if($slide->subtitle)
                        <p class="text-parchment font-heading text-uppercase mb-3" style="font-size: 15px; letter-spacing: 0.2em;" data-aos="zoom-in" data-aos-delay="200">
                            {{ $slide->subtitle }}
                        </p>
                        @endif

                        @if($slide->description)
                        <p class="text-parchment-dim mx-auto mb-4" style="max-width: 650px; line-height: 1.8;" data-aos="fade-up" data-aos-delay="300">
                            {{ $slide->description }}
                        </p>
                        @endif

                        <div class="d-flex flex-column flex-sm-row gap-3 justify-content-center mt-4" data-aos="zoom-in-up" data-aos-delay="400">
                            @if($slide->button_text && $slide->button_link)
                            <a href="{{ $slide->button_link }}" class="btn-gold-outline">
                                <span>{{ $slide->button_text }}</span>
                            </a>
                            @endif
                            <a href="{{ route('catalog') }}" class="btn-saffron-outline">
                                <span>Shop Catalog</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        @if($sliders->count() > 1)
        <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev" style="z-index: 10; width: 6%;" data-aos="fade-right">
            <span class="carousel-control-prev-icon" aria-hidden="true" style="filter: drop-shadow(0 0 4px rgba(212,175,55,0.8));"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next" style="z-index: 10; width: 6%;" data-aos="fade-left">
            <span class="carousel-control-next-icon" aria-hidden="true" style="filter: drop-shadow(0 0 4px rgba(212,175,55,0.8));"></span>
            <span class="visually-hidden">Next</span>
        </button>
        @endif
    </div>
</section>
@else
<!-- Fallback Hero Section -->
<section class="position-relative min-vh-100 d-flex align-items-center justify-content-center overflow-hidden pt-5" data-aos="fade">
    <div class="position-absolute top-0 start-0 w-100 h-100" style="z-index: 1;">
        <img src="https://images.unsplash.com/photo-1544025162-d76694265947?w=1800&q=90" alt="Premium halal meats" class="w-100 h-100 object-fit-cover" style="filter: brightness(0.25) saturate(0.85);">
        <div class="position-absolute top-0 start-0 w-100 h-100" style="background: linear-gradient(135deg, rgba(13,23,13,0.75) 0%, rgba(13,23,13,0.35) 50%, rgba(13,23,13,0.85) 100%);"></div>
        <div class="position-absolute m-3 m-md-5 pointer-events-none" style="top:0; bottom:0; left:0; right:0; border: 1px solid rgba(212,175,55,0.2);"></div>
    </div>

    <div class="position-relative text-center px-4 py-5" style="z-index: 2; max-width: 900px;">
        <p class="section-label mb-4" style="letter-spacing: 0.4em;" data-aos="fade-down" data-aos-duration="700">✦ Cary, North Carolina ✦</p>

        <h1 class="font-heading font-black text-uppercase text-gold mb-4" style="font-size: clamp(2.8rem, 7vw, 6.5rem); line-height: 1.05; letter-spacing: 0.03em; text-shadow: 0 0 70px rgba(212,175,55,0.3);" data-aos="zoom-in" data-aos-duration="900">
            Halal.<br>
            <span class="text-parchment">The Art</span><br>
            of Flavor.
        </h1>

        <p class="text-parchment-dim text-uppercase mb-5" style="font-size: 13px; letter-spacing: 0.28em;" data-aos="fade-up" data-aos-delay="200">
            Premium Halal Meats · Seafood · Groceries
        </p>

        <div class="d-flex flex-column flex-sm-row gap-3 justify-content-center" data-aos="zoom-in-up" data-aos-delay="300">
            <a href="{{ route('products') }}" class="btn-gold-outline">
                <span>Explore Our Offerings</span>
            </a>
            <a href="{{ route('catalog') }}" class="btn-saffron-outline">
                <span>Shop Catalog</span>
            </a>
        </div>

        <div class="mt-5 pt-3 d-flex flex-column align-items-center opacity-75" data-aos="fade-down" data-aos-delay="500">
            <span class="text-uppercase small" style="font-size: 9px; letter-spacing: 0.35em; color: rgba(212,175,55,0.6);">Scroll</span>
            <div class="scroll-line"></div>
        </div>
    </div>
</section>
@endif

<!-- Highlights Strip -->
<section style="background-color: var(--obsidian-surface); border-top: 1px solid var(--border-gold); border-bottom: 1px solid var(--border-gold);" data-aos="fade-down" data-aos-duration="800">
    <div class="container-xl py-4">
        <div class="row g-4 text-center">
            @foreach($highlights as $h)
            <div class="col-6 col-md-3" data-aos="{{ $loop->index === 0 ? 'zoom-in-right' : ($loop->index === 1 ? 'zoom-in-up' : ($loop->index === 2 ? 'zoom-in-down' : 'zoom-in-left')) }}" data-aos-delay="{{ $loop->index * 120 }}">
                <div class="d-flex flex-column align-items-center gap-1">
                    <i class="bi {{ $h['icon'] }} fs-3 text-gold mb-1" data-aos="flip-up" data-aos-delay="{{ ($loop->index * 120) + 100 }}"></i>
                    <span class="font-heading text-uppercase text-parchment fw-bold" style="font-size: 13px; letter-spacing: 0.15em;">{{ $h['label'] }}</span>
                    <span class="text-parchment-muted" style="font-size: 11px;">{{ $h['sub'] }}</span>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Curated Selection Section -->
<section class="py-5 my-md-5 overflow-hidden">
    <div class="container-xl py-4">

        <div class="text-center mb-5" data-aos="fade-down" data-aos-duration="700">
            <p class="section-label mb-2">Our Selection</p>
            <h2 class="font-heading font-black text-uppercase text-parchment" style="font-size: clamp(2rem, 4.5vw, 3.5rem);">
                Curated for <span class="text-gold">Discerning</span> Palates
            </h2>
            <div class="divider-diamonds" style="max-width: 250px;">
                <div class="line"></div>
                <div class="diamond"></div>
                <div class="diamond main"></div>
                <div class="diamond"></div>
                <div class="line"></div>
            </div>
        </div>

        <div class="row g-4">
            <!-- Card 1: Prime Rib-Eye Steak -->
            <div class="col-md-4" data-aos="zoom-in-right" data-aos-duration="750">
                <div class="luxury-card h-100 d-flex flex-column">
                    <div class="card-img-wrapper position-relative" style="height: 260px;">
                        <img src="https://images.unsplash.com/photo-1558030006-450675393462?w=800&q=85" alt="Prime Rib-Eye Steak" class="w-100 h-100 object-fit-cover">
                        <div class="position-absolute top-0 start-0 w-100 h-100" style="background: linear-gradient(to bottom, transparent 40%, rgba(13,23,13,0.9));"></div>
                        <span class="position-absolute top-3 start-3 badge text-gold border" style="border-color: rgba(212,175,55,0.4) !important; background: rgba(13,23,13,0.85); font-size: 9px; letter-spacing: 0.25em;">BEEF</span>
                    </div>
                    <div class="p-4 d-flex flex-column flex-grow-1">
                        <h3 class="font-heading text-uppercase text-gold fw-bold fs-5 mb-2" style="letter-spacing: 0.05em;">Prime Rib-Eye Steak</h3>
                        <p class="text-parchment-dim small flex-grow-1 mb-4" style="line-height: 1.7;">
                            Dry-aged, heavily marbled cut from the rib section. Rich flavor, ideal for grilling, searing, and special occasions.
                        </p>
                        <a href="{{ route('products') }}" class="text-gold text-uppercase fw-semibold small text-decoration-none d-flex align-items-center gap-2" style="letter-spacing: 0.2em; font-size: 11px;">
                            View Details <i class="bi bi-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Card 2: Fresh Goat (Full) -->
            <div class="col-md-4" data-aos="zoom-in-up" data-aos-duration="750" data-aos-delay="150">
                <div class="luxury-card h-100 d-flex flex-column">
                    <div class="card-img-wrapper position-relative" style="height: 260px;">
                        <img src="https://images.unsplash.com/photo-1574672280600-4accfa5b6f98?w=800&q=85" alt="Fresh Goat" class="w-100 h-100 object-fit-cover">
                        <div class="position-absolute top-0 start-0 w-100 h-100" style="background: linear-gradient(to bottom, transparent 40%, rgba(13,23,13,0.9));"></div>
                        <span class="position-absolute top-3 start-3 badge text-gold border" style="border-color: rgba(212,175,55,0.4) !important; background: rgba(13,23,13,0.85); font-size: 9px; letter-spacing: 0.25em;">GOAT</span>
                    </div>
                    <div class="p-4 d-flex flex-column flex-grow-1">
                        <h3 class="font-heading text-uppercase text-gold fw-bold fs-5 mb-2" style="letter-spacing: 0.05em;">Fresh Goat (Full)</h3>
                        <p class="text-parchment-dim small flex-grow-1 mb-4" style="line-height: 1.7;">
                            Whole halal goat, freshly slaughtered. Custom hand-cut to your exact specification. Perfect for biryani, curry, and feasts.
                        </p>
                        <a href="{{ route('products') }}" class="text-gold text-uppercase fw-semibold small text-decoration-none d-flex align-items-center gap-2" style="letter-spacing: 0.2em; font-size: 11px;">
                            View Details <i class="bi bi-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Card 3: South Asian Seafood -->
            <div class="col-md-4" data-aos="zoom-in-left" data-aos-duration="750" data-aos-delay="300">
                <div class="luxury-card h-100 d-flex flex-column">
                    <div class="card-img-wrapper position-relative" style="height: 260px;">
                        <img src="https://images.unsplash.com/photo-1510130387422-82bed34b37e9?w=800&q=85" alt="Fresh Rohu & Katla Seafood" class="w-100 h-100 object-fit-cover">
                        <div class="position-absolute top-0 start-0 w-100 h-100" style="background: linear-gradient(to bottom, transparent 40%, rgba(13,23,13,0.9));"></div>
                        <span class="position-absolute top-3 start-3 badge text-gold border" style="border-color: rgba(212,175,55,0.4) !important; background: rgba(13,23,13,0.85); font-size: 9px; letter-spacing: 0.25em;">SEAFOOD</span>
                    </div>
                    <div class="p-4 d-flex flex-column flex-grow-1">
                        <h3 class="font-heading text-uppercase text-gold fw-bold fs-5 mb-2" style="letter-spacing: 0.05em;">Fresh Rohu, Katla & Hilsa</h3>
                        <p class="text-parchment-dim small flex-grow-1 mb-4" style="line-height: 1.7;">
                            The finest Indian and Bangladeshi freshwater fish varieties. Prized for traditional fish curries, mustard gravy, and fry recipes.
                        </p>
                        <a href="{{ route('products') }}" class="text-gold text-uppercase fw-semibold small text-decoration-none d-flex align-items-center gap-2" style="letter-spacing: 0.2em; font-size: 11px;">
                            View Details <i class="bi bi-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="text-center mt-5" data-aos="zoom-out-up" data-aos-duration="750">
            <a href="{{ route('products') }}" class="btn-gold-outline">
                <span>View All Products & Pricing</span>
            </a>
        </div>

    </div>
</section>

<!-- Our Story / Butcher Craft Section -->
<section class="py-5 overflow-hidden" style="background-color: var(--obsidian-surface); border-top: 1px solid var(--border-gold); border-bottom: 1px solid var(--border-gold);">
    <div class="container-xl py-4">
        <div class="row g-5 align-items-center">

            <div class="col-lg-6" data-aos="flip-left" data-aos-duration="850">
                <div class="position-relative">
                    <img src="https://images.unsplash.com/photo-1607623814075-e51df1bdc82f?w=800&q=85" alt="Halal butcher at work" class="w-100 object-fit-cover shadow-lg" style="height: 420px; border: 1px solid rgba(212,175,55,0.3); filter: brightness(0.75) saturate(0.85);">
                    <div class="position-absolute d-none d-sm-block pointer-events-none" style="top: -15px; left: -15px; right: 15px; bottom: 15px; border: 1px solid rgba(212,175,55,0.15);"></div>
                    <div class="position-absolute bottom-0 end-0 m-3 px-3 py-2" style="background: rgba(13,23,13,0.9); border: 1px solid rgba(212,175,55,0.4);" data-aos="zoom-in-up-left" data-aos-delay="200">
                        <span class="text-gold text-uppercase small fw-bold" style="letter-spacing: 0.25em; font-size: 10px;">Est. West Cary, NC</span>
                    </div>
                </div>
            </div>

            <div class="col-lg-6 ps-lg-5" data-aos="fade-left" data-aos-duration="850">
                <p class="section-label mb-2">Our Story</p>
                <h2 class="font-heading font-black text-uppercase text-parchment mb-4" style="font-size: clamp(2rem, 3.5vw, 3rem);">
                    Where Faith Meets <span class="text-gold">Excellence</span>
                </h2>
                <p class="text-parchment-dim mb-3" style="line-height: 1.8;">
                    AZ Halal Marts was built on one conviction: the Muslim community deserves access to premium-grade halal meats without compromise. Every cut is hand-selected, every animal Zabiha-certified.
                </p>
                <p class="text-parchment-dim mb-4" style="line-height: 1.8;">
                    From succulent T-bone steaks and rib-eyes to the finest Indian and Bangladeshi fish — Rohu, Katla, Hilsa — we source with intention and serve with pride.
                </p>
                <div class="divider-diamonds mb-4" style="margin-left: 0; max-width: 250px;">
                    <div class="line"></div>
                    <div class="diamond"></div>
                    <div class="diamond main"></div>
                    <div class="diamond"></div>
                    <div class="line"></div>
                </div>
                <div>
                    <a href="{{ route('about') }}" class="btn-gold-outline">
                        <span>Our Full Story</span>
                    </a>
                </div>
            </div>

        </div>
    </div>
</section>

<!-- Ready to Order / Delivery Banner -->
<section class="py-5 text-center overflow-hidden">
    <div class="container-xl py-4">
        <div class="p-5 mx-auto" style="max-width: 800px; background-color: var(--obsidian-card); border: 1px solid var(--border-gold);" data-aos="zoom-in" data-aos-duration="800">
            <p class="section-label mb-3">Ready to Order?</p>
            <h2 class="font-heading font-black text-uppercase text-parchment mb-3" style="font-size: clamp(1.8rem, 3vw, 2.5rem);" data-aos="zoom-out-down" data-aos-delay="100">
                Free Delivery Within <span class="text-gold">10 Miles</span>
            </h2>
            <p class="text-parchment-dim mb-4" style="max-width: 500px; margin: 0 auto 1.5rem;" data-aos="fade-up" data-aos-delay="200">
                Call or WhatsApp us to place your order. We prepare custom cuts and package with precision.
            </p>
            <div class="d-flex flex-column flex-sm-row gap-3 justify-content-center" data-aos="zoom-in-up" data-aos-delay="300">
                <a href="tel:9192448634" class="btn-gold-outline">
                    <span>📞 919-244-8634</span>
                </a>
                <a href="https://wa.me/19192448634" target="_blank" rel="noopener noreferrer" class="btn-gold-outline">
                    <span>💬 WhatsApp Order</span>
                </a>
            </div>
        </div>
    </div>
</section>

@endsection
