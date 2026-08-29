@extends('layouts.app')

@section('title', 'Checkout · AZ Halal Marts · Complete Your Halal Order')

@section('content')

<section class="py-5" style="padding-top: 140px !important;">
    <div class="container-xl" style="max-width: 1000px;">
        
        <div class="text-center mb-5">
            <p class="section-label mb-2">Secure Order</p>
            <h1 class="font-heading font-black text-uppercase text-parchment mb-2" style="font-size: clamp(2rem, 4vw, 3.5rem);">
                Complete <span class="text-gold">Checkout</span>
            </h1>
            <p class="text-parchment-dim mx-auto mb-0" style="max-width: 500px;">
                Review your items and enter delivery details. Same-day delivery across Cary & the Triangle within 10 miles.
            </p>
        </div>

        <div class="row g-4" id="checkoutMainContainer">
            
            <!-- Checkout Form -->
            <div class="col-lg-7">
                <div class="luxury-card p-4 p-md-5">
                    <h4 class="font-heading text-gold mb-4 fw-bold text-uppercase" style="letter-spacing: 0.1em;">1. Customer & Delivery Info</h4>

                    <form id="checkoutOrderForm">
                        @csrf
                        <div class="row g-3">
                            <div class="col-12">
                                <label for="customer_name" class="form-label small text-uppercase text-gold fw-semibold">Full Name *</label>
                                <input type="text" name="customer_name" id="customer_name" class="gold-input" required placeholder="Rashid Khan">
                            </div>

                            <div class="col-md-6">
                                <label for="customer_phone" class="form-label small text-uppercase text-gold fw-semibold">Phone Number *</label>
                                <input type="tel" name="customer_phone" id="customer_phone" class="gold-input" required placeholder="919-555-0199">
                            </div>

                            <div class="col-md-6">
                                <label for="customer_email" class="form-label small text-uppercase text-gold fw-semibold">Email Address *</label>
                                <input type="email" name="customer_email" id="customer_email" class="gold-input" required placeholder="rashid@example.com">
                            </div>

                            <div class="col-12">
                                <label class="form-label small text-uppercase text-gold fw-semibold mb-2">Delivery Method *</label>
                                <div class="d-flex gap-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="delivery_type" id="dt_delivery" value="delivery" checked>
                                        <label class="form-check-label text-parchment" for="dt_delivery">Home Delivery (Free within 10 mi)</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="delivery_type" id="dt_pickup" value="pickup">
                                        <label class="form-check-label text-parchment" for="dt_pickup">Store Pickup (Cary)</label>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12" id="addressFieldGroup">
                                <label for="delivery_address" class="form-label small text-uppercase text-gold fw-semibold">Delivery Address *</label>
                                <input type="text" name="delivery_address" id="delivery_address" class="gold-input" placeholder="Street Address, Apt / Suite">
                            </div>

                            <div class="col-md-6" id="cityFieldGroup">
                                <label for="city" class="form-label small text-uppercase text-gold fw-semibold">City</label>
                                <input type="text" name="city" id="city" class="gold-input" value="Cary">
                            </div>

                            <div class="col-md-6" id="postalFieldGroup">
                                <label for="postal_code" class="form-label small text-uppercase text-gold fw-semibold">Zip Code</label>
                                <input type="text" name="postal_code" id="postal_code" class="gold-input" value="27519">
                            </div>

                            <div class="col-12">
                                <label for="notes" class="form-label small text-uppercase text-gold fw-semibold">Butcher Cutting Notes & Delivery Instructions</label>
                                <textarea name="notes" id="notes" rows="2" class="gold-input" placeholder="e.g. Cut beef into curry pieces, keep fat trimmed, leave at door..."></textarea>
                            </div>

                            <div class="col-12 pt-3">
                                <label class="form-label small text-uppercase text-gold fw-semibold mb-2">Payment Method *</label>
                                <div class="p-3 border border-secondary border-opacity-25 rounded bg-dark bg-opacity-50">
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="radio" name="payment_method" id="pm_cod" value="cash_on_delivery" checked>
                                        <label class="form-check-label text-parchment fw-semibold" for="pm_cod">
                                            💵 Pay on Delivery / Pickup (Cash or Card)
                                        </label>
                                    </div>
                                    <p class="text-parchment-muted small mb-0 ps-4">Pay our driver upon arrival or at the counter during store pickup.</p>
                                </div>
                            </div>

                            <div class="col-12 pt-4">
                                <button type="submit" id="btnSubmitOrder" class="btn-gold-outline w-100 py-3 text-center">
                                    <span>Place Order Now</span>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Order Summary Sidebar -->
            <div class="col-lg-5">
                <div class="luxury-card p-4 p-md-5">
                    <h4 class="font-heading text-gold mb-4 fw-bold text-uppercase" style="letter-spacing: 0.1em;">2. Order Summary</h4>

                    <div id="checkoutItemsList" class="d-flex flex-column gap-3 mb-4">
                        <!-- Populated by JS -->
                    </div>

                    <div class="pt-3 border-top border-secondary border-opacity-25 d-flex flex-column gap-2">
                        <div class="d-flex justify-content-between small text-parchment-dim">
                            <span>Subtotal:</span>
                            <span id="checkoutSubtotal" class="fw-semibold text-parchment">$0.00</span>
                        </div>
                        <div class="d-flex justify-content-between small text-parchment-dim">
                            <span>Estimated Delivery:</span>
                            <span id="checkoutDeliveryFee" class="text-success fw-semibold">FREE</span>
                        </div>
                        <div class="d-flex justify-content-between font-heading fs-5 text-gold fw-bold pt-2 border-top border-secondary border-opacity-25">
                            <span>Total Due:</span>
                            <span id="checkoutTotalAmount">$0.00</span>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>
</section>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('checkoutOrderForm');
    const itemsList = document.getElementById('checkoutItemsList');
    const subtotalEl = document.getElementById('checkoutSubtotal');
    const totalEl = document.getElementById('checkoutTotalAmount');
    const btnSubmit = document.getElementById('btnSubmitOrder');

    function renderCheckoutItems() {
        const items = window.AZCart ? window.AZCart.items : [];
        if (!items || items.length === 0) {
            itemsList.innerHTML = '<p class="text-parchment-muted small text-center my-3">No items in your cart. <a href="{{ route('catalog') }}" class="text-gold">Browse Catalog</a></p>';
            if (btnSubmit) btnSubmit.disabled = true;
            return;
        }

        if (btnSubmit) btnSubmit.disabled = false;

        let subtotal = 0;
        itemsList.innerHTML = items.map(i => {
            const itemTotal = i.price * i.quantity;
            subtotal += itemTotal;
            return `
                <div class="d-flex align-items-center justify-content-between gap-2 pb-2 border-bottom border-secondary border-opacity-15">
                    <div class="d-flex align-items-center gap-2 min-w-0">
                        ${i.img ? `<img src="${i.img}" style="width:36px;height:36px;object-fit:cover;border-radius:3px;">` : ''}
                        <div class="min-w-0">
                            <div class="text-truncate small fw-semibold text-parchment">${i.name}</div>
                            <span class="text-parchment-muted" style="font-size:11px;">Qty: ${i.quantity} × $${i.price.toFixed(2)}</span>
                        </div>
                    </div>
                    <span class="font-heading text-gold fw-bold small flex-shrink-0">$${itemTotal.toFixed(2)}</span>
                </div>
            `;
        }).join('');

        if (subtotalEl) subtotalEl.textContent = '$' + subtotal.toFixed(2);
        if (totalEl) totalEl.textContent = '$' + subtotal.toFixed(2);
    }

    renderCheckoutItems();

    if (form) {
        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            const items = window.AZCart ? window.AZCart.items : [];
            if (items.length === 0) {
                alert('Your cart is empty.');
                return;
            }

            const formData = new FormData(form);
            const payload = {
                customer_name: formData.get('customer_name'),
                customer_email: formData.get('customer_email'),
                customer_phone: formData.get('customer_phone'),
                delivery_address: formData.get('delivery_address'),
                city: formData.get('city'),
                postal_code: formData.get('postal_code'),
                delivery_type: formData.get('delivery_type'),
                payment_method: formData.get('payment_method'),
                notes: formData.get('notes'),
                items: items,
            };

            btnSubmit.innerHTML = '<span>Processing Order...</span>';
            btnSubmit.disabled = true;

            try {
                const response = await fetch('{{ route('checkout.process') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify(payload)
                });

                const data = await response.json();
                if (data.success && data.redirect) {
                    if (window.AZCart) window.AZCart.clear();
                    window.location.href = data.redirect;
                } else {
                    alert('Could not process order. Please check inputs.');
                    btnSubmit.innerHTML = '<span>Place Order Now</span>';
                    btnSubmit.disabled = false;
                }
            } catch (err) {
                console.error(err);
                alert('An error occurred. Please try again.');
                btnSubmit.innerHTML = '<span>Place Order Now</span>';
                btnSubmit.disabled = false;
            }
        });
    }
});
</script>
@endpush
