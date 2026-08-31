@extends('layouts.app')

@section('title', 'Order Confirmed · AZ Halal Marts')

@section('content')

<section class="py-5 overflow-hidden" style="padding-top: 150px !important; min-height: 80vh;">
    <div class="container-xl text-center" style="max-width: 750px;">

        <!-- Animated Success Diamond -->
        <div class="d-inline-flex align-items-center justify-content-center mb-4 rounded-circle" style="width: 80px; height: 80px; background: rgba(212,175,55,0.15); border: 2px solid var(--gold);" data-aos="zoom-in" data-aos-duration="700">
            <i class="bi bi-check-lg text-gold fs-1"></i>
        </div>

        <div data-aos="fade-down" data-aos-duration="700">
            <p class="section-label mb-2">Order Received</p>
            <h1 class="font-heading font-black text-uppercase text-parchment mb-3" style="font-size: clamp(2rem, 5vw, 3.5rem);">
                Order <span class="text-gold">Confirmed</span>
            </h1>

            <p class="text-parchment-dim mx-auto mb-4" style="max-width: 550px; line-height: 1.8;">
                Thank you for ordering with AZ Halal Marts! Our master butchers are preparing your fresh cuts with utmost care and Zabiha halal precision.
            </p>
        </div>

        <!-- Order Reference Card -->
        <div class="luxury-card p-4 p-md-5 mx-auto mb-4 text-start" style="background-color: var(--obsidian-card);" data-aos="fade-up" data-aos-duration="700" data-aos-delay="100">
            <div class="d-flex justify-content-between align-items-center pb-3 mb-3 border-bottom border-secondary border-opacity-25">
                <div>
                    <span class="text-parchment-muted small text-uppercase" style="letter-spacing: 0.15em; font-size: 11px;">Order Reference</span>
                    <h4 class="font-heading text-gold fw-bold mb-0 mt-1">{{ $orderNumber }}</h4>
                </div>
                <span class="badge bg-success bg-opacity-25 text-success border border-success px-3 py-2 text-uppercase" style="font-size: 10px; letter-spacing: 0.1em;">
                    <i class="bi bi-hourglass-split me-1"></i> Queued for Prep
                </span>
            </div>

            @if(!empty($order) && $order->items->count() > 0)
            <div class="mb-3">
                <h6 class="small text-uppercase text-gold-light fw-bold mb-2">Items Ordered:</h6>
                @foreach($order->items as $item)
                <div class="d-flex justify-content-between small py-1 border-bottom border-secondary border-opacity-10 text-parchment-dim">
                    <span>{{ $item->product_name }} (x{{ $item->quantity }})</span>
                    <span class="text-gold fw-bold">${{ number_format($item->subtotal, 2) }}</span>
                </div>
                @endforeach
                <div class="d-flex justify-content-between font-heading fs-6 text-gold fw-bold pt-2 mt-1">
                    <span>Total Amount:</span>
                    <span>${{ number_format($order->total_amount, 2) }}</span>
                </div>
            </div>
            @endif

            <div class="d-flex flex-column gap-2 small text-parchment-dim mt-3 pt-3 border-top border-secondary border-opacity-25">
                @if(!empty($order) && $order->payment_method)
                <div>
                    <strong>💳 Payment Method:</strong>
                    <span class="text-parchment">
                        {{ $order->payment_method === 'cash_on_delivery' ? 'Cash on Delivery / Pickup' : 'Online Payment (Credit/Debit Card)' }}
                    </span>
                </div>
                @endif
                <div><strong>📍 Location:</strong> 716 Slash Pine Dr, Cary, NC 27519</div>
                <div><strong>📞 Direct Line:</strong> <a href="tel:9192448634" class="text-gold text-decoration-none">919-244-8634</a></div>
                <div><strong>🚚 Delivery Service:</strong> Free within 10 miles. Our driver will call prior to arrival.</div>
            </div>
        </div>

        <div class="d-flex flex-column flex-sm-row justify-content-center gap-3" data-aos="fade-up" data-aos-delay="300">
            <a href="{{ route('catalog') }}" class="btn-gold-outline">
                <span>Continue Shopping</span>
            </a>
            <a href="{{ route('home') }}" class="btn-saffron-outline">
                <span>Back to Home</span>
            </a>
        </div>

    </div>
</section>

@endsection

@push('scripts')
<script>
    if (window.AZCart) {
        window.AZCart.clear();
    }
</script>
@endpush
