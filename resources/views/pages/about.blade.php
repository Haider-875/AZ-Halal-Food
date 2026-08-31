@extends('layouts.app')

@section('title', 'About Us · AZ Halal Marts · Heritage & Quality Butchery')

@section('content')

<!-- Hero Section -->
<section class="position-relative overflow-hidden pt-5 pb-5" style="padding-top: 140px !important;">
    <div class="position-absolute top-0 start-0 w-100 h-100" style="z-index: 1;">
        <img src="https://images.unsplash.com/photo-1604908176997-125f25cc6f3d?w=1600&q=80" alt="Halal meat preparation" class="w-100 h-100 object-fit-cover" style="filter: brightness(0.2) saturate(0.7);">
        <div class="position-absolute top-0 start-0 w-100 h-100" style="background: linear-gradient(to bottom, transparent, #0D170D 80%);"></div>
    </div>

    <div class="position-relative text-center px-4 py-5" style="z-index: 2; max-width: 850px; margin: 0 auto;" data-aos="fade-down" data-aos-duration="800">
        <p class="section-label mb-3">Our Story</p>
        <h1 class="font-heading font-black text-uppercase text-parchment mb-3" style="font-size: clamp(2.5rem, 6vw, 5rem); line-height: 1.1;">
            A Legacy of <span class="text-gold">Halal</span> Excellence
        </h1>
        <div class="divider-diamonds" style="max-width: 250px;">
            <div class="line"></div>
            <div class="diamond"></div>
            <div class="diamond main"></div>
            <div class="diamond"></div>
            <div class="line"></div>
        </div>
    </div>
</section>

<!-- Heritage Narrative Section -->
<section class="py-5 overflow-hidden">
    <div class="container-xl">
        <div class="row g-5 align-items-center">

            <div class="col-lg-6" data-aos="fade-right" data-aos-duration="750">
                <div class="d-flex flex-column gap-3 text-parchment-dim" style="line-height: 1.8;">
                    <p>
                        <span class="font-heading font-bold text-gold fs-5">AZ Halal Marts</span> was founded with a simple but profound mission: to give the Muslim community in Cary, NC access to truly premium halal meat — not the frozen, generic product found in big-box stores, but hand-cut, freshly slaughtered, Zabiha-certified excellence.
                    </p>
                    <p>
                        Located at 716 Slash Pine Drive in West Cary, we serve the entire Triangle community including Morrisville, Apex, Durham, and beyond. Our butchers are trained craftsmen who understand the difference between a mediocre cut and one that will make your family's meal unforgettable.
                    </p>
                    <p>
                        Our seafood section is unmatched in the region — we source the finest Indian and Bangladeshi fish varieties: Rohu, Katla, and the prized Hilsa. These are not generic supermarket imports; they are carefully selected for freshness and quality.
                    </p>
                    <p>
                        In 2023, we added our Royal Pakistani Mango import program — bringing direct-from-orchard Chaunsa, Sindhri, and Anwar Ratol varieties to the NC community. If you've never tasted a truly ripe, zero-fiber Pakistani mango, your life is about to change.
                    </p>
                </div>
            </div>

            <div class="col-lg-6" data-aos="fade-left" data-aos-duration="750">
                <div class="position-relative">
                    <img src="https://images.unsplash.com/photo-1567620832903-9fc6debc209f?w=800&q=85" alt="Fresh halal meat cuts" class="w-100 object-fit-cover shadow-lg" style="height: 440px; border: 1px solid rgba(212,175,55,0.25); filter: brightness(0.75) saturate(0.85);">
                    <div class="position-absolute d-none d-sm-block pointer-events-none" style="bottom: -15px; right: -15px; left: 15px; top: 15px; border: 1px solid rgba(212,175,55,0.12);"></div>
                </div>
            </div>

        </div>
    </div>
</section>

<!-- 4 Core Pillars Section -->
<section class="py-5 overflow-hidden" style="background-color: var(--obsidian-surface); border-top: 1px solid var(--border-gold); border-bottom: 1px solid var(--border-gold);">
    <div class="container-xl py-4">

        <div class="text-center mb-5" data-aos="fade-down" data-aos-duration="700">
            <p class="section-label mb-2">Our Standards</p>
            <h2 class="font-heading font-black text-uppercase text-parchment" style="font-size: clamp(2rem, 4vw, 3rem);">
                The Pillars of <span class="text-gold">AZ Halal</span>
            </h2>
        </div>

        <div class="row g-4">
            @foreach($pillars as $p)
            <div class="col-md-6 col-lg-3" data-aos="zoom-in-up" data-aos-duration="700" data-aos-delay="{{ $loop->index * 100 }}">
                <div class="luxury-card h-100 p-4 d-flex flex-column text-center">
                    <i class="bi {{ $p['icon'] }} fs-2 text-gold mb-3 mx-auto"></i>
                    <h4 class="font-heading text-uppercase text-gold fw-bold fs-6 mb-3" style="letter-spacing: 0.1em;">{{ $p['title'] }}</h4>
                    <p class="text-parchment-dim small mb-0" style="line-height: 1.7;">{{ $p['desc'] }}</p>
                </div>
            </div>
            @endforeach
        </div>

    </div>
</section>

<!-- Milestones Timeline Section -->
<section class="py-5 my-md-4 overflow-hidden">
    <div class="container-xl py-4">

        <div class="text-center mb-5" data-aos="fade-down" data-aos-duration="700">
            <p class="section-label mb-2">Milestones</p>
            <h2 class="font-heading font-black text-uppercase text-parchment" style="font-size: clamp(2rem, 4vw, 3rem);">
                Our <span class="text-gold">Journey</span>
            </h2>
        </div>

        <div class="mx-auto" style="max-width: 750px;">
            <div class="d-flex flex-column gap-3">
                @foreach($timeline as $item)
                <div class="luxury-card p-4 d-flex align-items-center gap-4" data-aos="{{ $loop->even ? 'fade-left' : 'fade-right' }}" data-aos-duration="700" data-aos-delay="{{ $loop->index * 60 }}">
                    <div class="font-heading text-gold fw-bold fs-4 flex-shrink-0" style="width: 70px;">{{ $item['year'] }}</div>
                    <div class="text-parchment-dim small">{{ $item['event'] }}</div>
                </div>
                @endforeach
            </div>
        </div>

        <div class="text-center mt-5 pt-3" data-aos="zoom-in" data-aos-duration="700">
            <a href="{{ route('contact') }}" class="btn-gold-outline me-3 mb-2">
                <span>Visit Us in Cary</span>
            </a>
            <a href="{{ route('products') }}" class="btn-saffron-outline mb-2">
                <span>Explore Products</span>
            </a>
        </div>

    </div>
</section>

@endsection
