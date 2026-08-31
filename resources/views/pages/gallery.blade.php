@extends('layouts.app')

@section('title', 'Visual Archive · Gallery · AZ Halal Marts')

@section('content')

<!-- Hero Section -->
<section class="position-relative overflow-hidden pt-5 pb-5" style="padding-top: 140px !important;">
    <div class="position-absolute top-0 start-0 w-100 h-100" style="z-index: 1;">
        <img src="https://images.unsplash.com/photo-1567620905732-2d1ec7ab7445?w=1600&q=80" alt="Gallery hero" class="w-100 h-100 object-fit-cover" style="filter: brightness(0.18);">
        <div class="position-absolute top-0 start-0 w-100 h-100" style="background: linear-gradient(to bottom, transparent, #0D170D 80%);"></div>
    </div>

    <div class="position-relative text-center px-4 py-5" style="z-index: 2; max-width: 850px; margin: 0 auto;" data-aos="fade-down" data-aos-duration="700">
        <p class="section-label mb-3">Visual Archive</p>
        <h1 class="font-heading font-black text-uppercase text-parchment mb-3" style="font-size: clamp(2.5rem, 6vw, 5.5rem); line-height: 1.1;">
            The <span class="text-gold">Gallery</span>
        </h1>
        <p class="text-parchment-dim mx-auto mb-0" style="max-width: 600px; line-height: 1.8;">
            A curated visual record of our premium halal meats, fresh seafood, imported mangoes, and grocery selections.
        </p>
    </div>
</section>

<!-- Gallery Section -->
<section class="py-5 overflow-hidden">
    <div class="container-xl">

        <!-- Filter Tabs -->
        <div class="d-flex flex-wrap justify-content-center gap-2 mb-5" data-aos="fade-down" data-aos-duration="700">
            @foreach($tags as $t)
            <button type="button" class="btn-filter gallery-filter-btn {{ $loop->first ? 'active' : '' }}" data-tag="{{ $t }}">
                {{ $t }}
            </button>
            @endforeach
        </div>

        <!-- Masonry Grid -->
        <div class="gallery-grid">
            @foreach($items as $item)
            <div class="gallery-item" data-tag="{{ $item['tag'] }}" data-src="{{ $item['src'] }}" data-caption="{{ $item['caption'] }}" data-aos="zoom-in" data-aos-duration="650" data-aos-delay="{{ ($loop->index % 4) * 80 }}">
                <img src="{{ $item['src'] }}" alt="{{ $item['alt'] }}" loading="lazy">
                <div class="gallery-overlay">
                    <span class="badge text-gold border mb-2 align-self-start" style="border-color: rgba(212,175,55,0.4) !important; background: rgba(13,23,13,0.85); font-size: 8px; letter-spacing: 0.25em;">
                        {{ $item['tag'] }}
                    </span>
                    <h5 class="font-heading text-parchment text-uppercase fw-bold mb-0" style="font-size: 14px; letter-spacing: 0.05em;">
                        {{ $item['caption'] }}
                    </h5>
                </div>
            </div>
            @endforeach
        </div>

    </div>
</section>

<!-- Lightbox Modal -->
<div class="modal fade" id="galleryLightboxModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content text-parchment" style="background-color: var(--obsidian-surface); border: 1px solid var(--border-gold-bright);">
            <div class="modal-header border-bottom" style="border-color: var(--border-gold) !important;">
                <div>
                    <span id="lightboxTag" class="badge text-gold border me-2" style="border-color: rgba(212,175,55,0.4) !important; background: rgba(13,23,13,0.85); font-size: 9px; letter-spacing: 0.2em;"></span>
                    <span id="lightboxCaption" class="font-heading text-parchment text-uppercase fw-bold fs-6"></span>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0 text-center">
                <img id="lightboxImage" src="" alt="Enlarged gallery view" class="w-100 object-fit-contain" style="max-height: 75vh;">
            </div>
        </div>
    </div>
</div>

@endsection
