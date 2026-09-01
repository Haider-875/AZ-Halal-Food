@extends('layouts.app')

@section('title', 'Checkout · AZ Halal Marts · Complete Your Halal Order')

@section('content')

<section class="py-5 overflow-hidden" style="padding-top: 140px !important;">
    <div class="container-xl" style="max-width: 1000px;">

        <div class="text-center mb-5" data-aos="fade-down" data-aos-duration="700">
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
            <div class="col-lg-7" data-aos="fade-right" data-aos-duration="750">
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
                                <div class="d-flex flex-column gap-3">
                                    <!-- Option 1: Cash on Delivery -->
                                    <div class="p-3 border border-secondary border-opacity-25 rounded bg-dark bg-opacity-50 payment-method-card">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="payment_method" id="pm_cod" value="cash_on_delivery" checked>
                                            <label class="form-check-label text-parchment fw-semibold" for="pm_cod">
                                                💵 Cash on Delivery / Store Pickup
                                            </label>
                                        </div>
                                        <p class="text-parchment-muted small mb-0 ps-4">Pay in cash or card upon delivery to your doorstep, or at the store counter upon pickup.</p>
                                    </div>

                                    {{-- 
                                    <!-- Option 2: PayPal & Online Payment (Commented for now) -->
                                    <div class="p-3 border border-secondary border-opacity-25 rounded bg-dark bg-opacity-50 payment-method-card cursor-pointer" id="card_pm_paypal" onclick="selectPaymentMethod('paypal')">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="payment_method" id="pm_paypal" value="paypal" onchange="selectPaymentMethod('paypal')">
                                            <label class="form-check-label text-parchment fw-semibold d-flex align-items-center gap-2" for="pm_paypal">
                                                <i class="bi bi-paypal text-info fs-5"></i> Pay with PayPal / Debit or Credit Card (Online Payment)
                                            </label>
                                        </div>
                                        <p class="text-parchment-muted small mb-0 ps-4">Safe, instant, and encrypted checkout via PayPal balance, bank, or credit/debit card.</p>
                                    </div>
                                    --}}
                                </div>
                            </div>

                            <!-- COD Submit Button Container -->
                            <div class="col-12 pt-4" id="cod-button-container">
                                <button type="submit" id="btnSubmitOrder" class="btn-gold-outline w-100 py-3 text-center">
                                    <span>Place Order Now</span>
                                </button>
                            </div>

                            {{-- 
                            <!-- PayPal Payment Container (Commented for now) -->
                            <div class="col-12 pt-4 d-none" id="paypal-button-container">
                                <div class="p-4 border border-gold border-opacity-50 rounded bg-dark bg-opacity-90">
                                    <div class="d-flex align-items-center justify-content-between mb-3 pb-2 border-bottom border-secondary border-opacity-25">
                                        <div class="d-flex align-items-center gap-2">
                                            <i class="bi bi-paypal fs-4 text-info"></i>
                                            <div>
                                                <div class="text-parchment fw-bold small">PayPal Express Checkout</div>
                                                <div class="text-parchment-muted" style="font-size: 11px;">Sandbox & Live Payment Gateway</div>
                                            </div>
                                        </div>
                                        <span class="badge bg-gold text-dark px-2 py-1" style="font-size: 10px;">SECURE 256-BIT</span>
                                    </div>

                                    <div id="paypal-sdk-buttons" class="mb-3"></div>

                                    <div id="paypal-interactive-buttons" class="d-flex flex-column gap-2">
                                        <button type="button" id="btnPaypalExpress" class="btn w-100 py-2 d-flex align-items-center justify-content-center gap-2 fw-bold" style="background: #FFC439; color: #111; border-radius: 4px; font-size: 14px;">
                                            <i class="bi bi-paypal fs-5" style="color: #003087;"></i>
                                            <span>Pay with <strong style="color: #003087;">Pay</strong><strong style="color: #0079C1;">Pal</strong></span>
                                        </button>
                                        <button type="button" id="btnPaypalCard" class="btn w-100 py-2 d-flex align-items-center justify-content-center gap-2 fw-bold" style="background: #2C2E2F; color: #FFF; border: 1px solid #444; border-radius: 4px; font-size: 14px;">
                                            <i class="bi bi-credit-card-2-front text-gold"></i>
                                            <span>Debit or Credit Card</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            --}}

                        </div>
                    </form>
                </div>
            </div>

            <!-- Order Summary Sidebar -->
            <div class="col-lg-5" data-aos="fade-left" data-aos-duration="800">
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

                    <div class="mt-4 p-3 bg-dark bg-opacity-50 border border-secondary border-opacity-25 rounded">
                        <div class="d-flex align-items-center gap-2 text-gold small fw-semibold">
                            <i class="bi bi-shield-check fs-5"></i> 100% Halal Certified Guarantee
                        </div>
                        <div class="text-parchment-muted small mt-1" style="font-size: 11px;">
                            Hand-slaughtered strictly per Islamic guidelines. Freshness & satisfaction guaranteed.
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>
</section>

{{-- 
<!-- PayPal Sandbox Simulation Modal (Commented for now) -->
<div class="modal fade" id="paypalSandboxModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-gold" style="background: #0D170D; color: #EDE6D6; border: 1px solid rgba(212,175,55,0.4);">
            <div class="modal-header border-secondary border-opacity-25 py-3">
                <h5 class="modal-title font-heading text-gold mb-0 fw-bold fs-6">PayPal Checkout Sandbox</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4 text-center">
                <div class="font-heading text-gold fw-bold fs-3 mb-3" id="paypalModalAmount">$0.00 USD</div>
                <button type="button" id="btnConfirmPaypalPayment" class="btn w-100 py-3 fw-bold text-dark" style="background: #FFC439;">Complete Sandbox Payment</button>
            </div>
        </div>
    </div>
</div>
--}}

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('checkoutOrderForm');
    const itemsList = document.getElementById('checkoutItemsList');
    const subtotalEl = document.getElementById('checkoutSubtotal');
    const totalEl = document.getElementById('checkoutTotalAmount');
    const deliveryFeeEl = document.getElementById('checkoutDeliveryFee');
    const btnSubmit = document.getElementById('btnSubmitOrder');

    function getCartTotal() {
        const items = window.AZCart ? window.AZCart.items : [];
        let subtotal = 0;
        items.forEach(i => { subtotal += (i.price * i.quantity); });
        const deliveryType = form ? form.querySelector('input[name="delivery_type"]:checked')?.value : 'delivery';
        const fee = (deliveryType === 'delivery' && subtotal < 50 && subtotal > 0) ? 5.00 : 0.00;
        return { subtotal, fee, total: subtotal + fee };
    }

    function renderCheckoutItems() {
        const items = window.AZCart ? window.AZCart.items : [];
        if (!items || items.length === 0) {
            itemsList.innerHTML = '<p class="text-parchment-muted small text-center my-3">No items in your cart. <a href="{{ route('catalog') }}" class="text-gold">Browse Catalog</a></p>';
            if (btnSubmit) btnSubmit.disabled = true;
            return;
        }

        if (btnSubmit) btnSubmit.disabled = false;

        const { subtotal, fee, total } = getCartTotal();

        itemsList.innerHTML = items.map(i => {
            const itemTotal = i.price * i.quantity;
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
        if (deliveryFeeEl) deliveryFeeEl.textContent = fee > 0 ? '$' + fee.toFixed(2) : 'FREE';
        if (totalEl) totalEl.textContent = '$' + total.toFixed(2);
    }

    renderCheckoutItems();

    // Delivery type change updates summary
    const dtInputs = document.querySelectorAll('input[name="delivery_type"]');
    dtInputs.forEach(input => {
        input.addEventListener('change', renderCheckoutItems);
    });

    function validateCustomerForm() {
        if (!form) return false;
        const name = form.querySelector('[name="customer_name"]')?.value.trim();
        const email = form.querySelector('[name="customer_email"]')?.value.trim();
        const phone = form.querySelector('[name="customer_phone"]')?.value.trim();
        const deliveryType = form.querySelector('[name="delivery_type"]:checked')?.value;
        const address = form.querySelector('[name="delivery_address"]')?.value.trim();

        if (!name) { alert('Please enter your full name.'); form.querySelector('[name="customer_name"]')?.focus(); return false; }
        if (!email) { alert('Please enter your email address.'); form.querySelector('[name="customer_email"]')?.focus(); return false; }
        if (!phone) { alert('Please enter your phone number.'); form.querySelector('[name="customer_phone"]')?.focus(); return false; }
        if (deliveryType === 'delivery' && !address) {
            alert('Please enter your delivery street address.');
            form.querySelector('[name="delivery_address"]')?.focus();
            return false;
        }

        const items = window.AZCart ? window.AZCart.items : [];
        if (!items || items.length === 0) {
            alert('Your cart is empty. Please add items to your cart.');
            return false;
        }

        return true;
    }

    // Standard Order Submission (Cash on Delivery)
    if (form) {
        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            if (!validateCustomerForm()) return;

            const items = window.AZCart ? window.AZCart.items : [];
            const formData = new FormData(form);
            const payload = {
                customer_name: formData.get('customer_name'),
                customer_email: formData.get('customer_email'),
                customer_phone: formData.get('customer_phone'),
                delivery_address: formData.get('delivery_address'),
                city: formData.get('city'),
                postal_code: formData.get('postal_code'),
                delivery_type: formData.get('delivery_type'),
                payment_method: 'cash_on_delivery',
                notes: formData.get('notes'),
                items: items,
            };

            btnSubmit.innerHTML = '<span>Placing Order...</span>';
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
                    alert(data.message || 'Could not process order. Please check your inputs.');
                    btnSubmit.innerHTML = '<span>Place Order Now</span>';
                    btnSubmit.disabled = false;
                }
            } catch (err) {
                console.error(err);
                alert('An error occurred while placing your order. Please try again.');
                btnSubmit.innerHTML = '<span>Place Order Now</span>';
                btnSubmit.disabled = false;
            }
        });
    }
});
</script>
@endpush
