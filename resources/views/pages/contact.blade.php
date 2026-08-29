@extends('layouts.app')

@section('title', 'Contact Us & Store Location · AZ Halal Marts · West Cary, NC')

@section('content')

<!-- Hero Section -->
<section class="position-relative overflow-hidden pt-5 pb-5" style="padding-top: 140px !important;">
    <div class="position-absolute top-0 start-0 w-100 h-100" style="z-index: 1;">
        <img src="https://images.unsplash.com/photo-1542838132-92c53300491e?w=1600&q=80" alt="Halal grocery store" class="w-100 h-100 object-fit-cover" style="filter: brightness(0.18) saturate(0.7);">
        <div class="position-absolute top-0 start-0 w-100 h-100" style="background: linear-gradient(to bottom, transparent, #0D170D 75%);"></div>
    </div>

    <div class="position-relative text-center px-4 py-5" style="z-index: 2; max-width: 850px; margin: 0 auto;">
        <p class="section-label mb-3">Get In Touch</p>
        <h1 class="font-heading font-black text-uppercase text-parchment mb-3" style="font-size: clamp(2.5rem, 6vw, 5.5rem); line-height: 1.1;">
            Visit <span class="text-gold">AZ Halal Marts</span>
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

<!-- Contact Cards Section -->
<section class="py-5">
    <div class="container-xl">
        
        <div class="row g-4 mb-5">
            <!-- Location Card -->
            <div class="col-md-4">
                <div class="luxury-card h-100 p-4 p-lg-5 d-flex flex-column">
                    <i class="bi bi-geo-alt fs-3 text-gold mb-3"></i>
                    <h3 class="font-heading text-uppercase text-gold fw-bold fs-5 mb-3" style="letter-spacing: 0.1em;">Location</h3>
                    <p class="text-parchment-dim mb-4" style="line-height: 1.8;">
                        <strong>716 Slash Pine Dr</strong><br>
                        Cary, NC 27519<br>
                        <span class="text-parchment-muted">(West Cary)</span>
                    </p>
                    <a href="https://maps.google.com/?q=716+Slash+Pine+Dr,+Cary,+NC+27519" target="_blank" rel="noopener noreferrer" class="text-gold text-uppercase fw-semibold small text-decoration-none mt-auto d-flex align-items-center gap-2" style="letter-spacing: 0.2em; font-size: 11px;">
                        Open in Maps <i class="bi bi-arrow-up-right"></i>
                    </a>
                </div>
            </div>

            <!-- Phone & Social Card -->
            <div class="col-md-4">
                <div class="luxury-card h-100 p-4 p-lg-5 d-flex flex-column">
                    <i class="bi bi-telephone fs-3 text-gold mb-3"></i>
                    <h3 class="font-heading text-uppercase text-gold fw-bold fs-5 mb-3" style="letter-spacing: 0.1em;">Phone & Social</h3>
                    <div class="d-flex flex-column gap-2 mb-4">
                        <a href="tel:9192448634" class="text-parchment-dim text-decoration-none hover-gold small">
                            📞 919-244-8634
                        </a>
                        <a href="tel:9193441125" class="text-parchment-dim text-decoration-none hover-gold small">
                            📞 919-344-1125
                        </a>
                        <a href="https://wa.me/19192448634" target="_blank" rel="noopener noreferrer" class="text-gold text-decoration-none small">
                            💬 WhatsApp Order
                        </a>
                    </div>
                    <div class="d-flex gap-3 mt-auto pt-3 border-top" style="border-color: rgba(212,175,55,0.15) !important;">
                        <a href="https://instagram.com/azhalalmarts" target="_blank" rel="noopener noreferrer" class="text-gold text-decoration-none small d-flex align-items-center gap-1">
                            <i class="bi bi-instagram"></i> Instagram
                        </a>
                        <a href="https://facebook.com/AZHALALMARTS" target="_blank" rel="noopener noreferrer" class="text-gold text-decoration-none small d-flex align-items-center gap-1">
                            <i class="bi bi-facebook"></i> Facebook
                        </a>
                    </div>
                </div>
            </div>

            <!-- Store Hours Card -->
            <div class="col-md-4">
                <div class="luxury-card h-100 p-4 p-lg-5 d-flex flex-column">
                    <i class="bi bi-clock-history fs-3 text-gold mb-3"></i>
                    <h3 class="font-heading text-uppercase text-gold fw-bold fs-5 mb-3" style="letter-spacing: 0.1em;">Store Hours</h3>
                    <ul class="list-unstyled d-flex flex-column gap-2 small mb-0">
                        @foreach($hours as $h)
                        <li class="d-flex justify-content-between py-1 border-bottom {{ $h['day'] === $today ? 'text-gold fw-bold' : 'text-parchment-dim' }}" style="border-color: rgba(212,175,55,0.08) !important;">
                            <span>{{ $h['day'] }}</span>
                            <span class="{{ $h['day'] === $today ? 'text-gold' : 'text-parchment-muted' }}">{{ $h['hours'] }}</span>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>

        <!-- Google Maps Embed -->
        <div class="luxury-card p-1 mb-5">
            <iframe title="AZ Halal Marts Location" src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3237.897!2d-78.908450!3d35.830783!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x89ac9a9a7f5eeef1%3A0x1!2s716+Slash+Pine+Dr%2C+Cary%2C+NC+27519!5e0!3m2!1sen!2sus!4v1680000000000" width="100%" height="380" style="border:0; filter: invert(90%) hue-rotate(180deg) saturate(0.3) brightness(0.8);" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
        </div>

        <!-- Wholesale & Business Inquiry Form -->
        <div class="mx-auto" style="max-width: 800px;">
            <div class="text-center mb-4">
                <p class="section-label">Business Inquiries</p>
                <h2 class="font-heading font-black text-uppercase text-parchment mb-2" style="font-size: clamp(2rem, 4vw, 3rem);">
                    Wholesale & <span class="text-gold">Partnerships</span>
                </h2>
                <p class="text-parchment-dim small">
                    Restaurants, catering companies, and bulk buyers — send us your inquiry for competitive wholesale pricing.
                </p>
            </div>

            @if(session('inquiry_received'))
            <div class="luxury-card p-5 text-center my-4">
                <i class="bi bi-check-circle text-gold fs-1 mb-3"></i>
                <h3 class="font-heading text-gold text-uppercase fw-bold fs-4 mb-2">Inquiry Received</h3>
                <p class="text-parchment-dim mb-0">
                    Thank you, <strong>{{ session('name') }}</strong>. We'll get back to you at <strong>{{ session('email') }}</strong> within 24–48 hours.
                </p>
            </div>
            @else
            <div class="luxury-card p-4 p-md-5">
                
                <!-- Inquiry Type Selector -->
                <div class="mb-4">
                    <label class="section-label mb-2" style="font-size: 10px;">Inquiry Type</label>
                    <div class="d-flex flex-wrap gap-2">
                        <button type="button" class="btn-filter inquiry-type-btn active" data-type="Wholesale Meat">Wholesale Meat</button>
                        <button type="button" class="btn-filter inquiry-type-btn" data-type="Mango Import">Mango Import</button>
                        <button type="button" class="btn-filter inquiry-type-btn" data-type="Catering">Catering</button>
                        <button type="button" class="btn-filter inquiry-type-btn" data-type="General">General</button>
                    </div>
                </div>

                <form action="{{ route('inquiry.store') }}" method="POST" class="d-flex flex-column gap-4">
                    @csrf
                    <input type="hidden" name="inquiry_type" id="inquiryTypeInput" value="Wholesale Meat">

                    <div class="row g-4">
                        <div class="col-md-6">
                            <label class="section-label mb-2" style="font-size: 10px;">Full Name *</label>
                            <input type="text" name="name" class="gold-input" placeholder="e.g. Tariq Khan" required>
                        </div>
                        <div class="col-md-6">
                            <label class="section-label mb-2" style="font-size: 10px;">Company / Restaurant</label>
                            <input type="text" name="company" class="gold-input" placeholder="e.g. Triangle Grill">
                        </div>
                        <div class="col-md-6">
                            <label class="section-label mb-2" style="font-size: 10px;">Email Address *</label>
                            <input type="email" name="email" class="gold-input" placeholder="name@domain.com" required>
                        </div>
                        <div class="col-md-6">
                            <label class="section-label mb-2" style="font-size: 10px;">Phone Number</label>
                            <input type="tel" name="phone" class="gold-input" placeholder="(919) 000-0000">
                        </div>
                    </div>

                    <div>
                        <label class="section-label mb-2" style="font-size: 10px;">Message *</label>
                        <textarea name="message" rows="4" class="gold-input" placeholder="Describe your wholesale or catering inquiry, meat requirements, volume..." required></textarea>
                    </div>

                    <button type="submit" class="btn-gold-outline w-100 py-3 text-center">
                        <span>Send Inquiry</span>
                    </button>
                </form>
            </div>
            @endif

        </div>

    </div>
</section>

@endsection
