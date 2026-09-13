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

        <!-- Dedicated Bypass Lockout Screen when order > $50 -->
        <div id="checkoutBypassLockoutScreen" class="d-none my-4">
            <div class="luxury-card p-4 p-md-5 text-center mx-auto" style="max-width: 650px;">
                <div class="mb-4">
                    <span class="d-inline-flex align-items-center justify-content-center rounded-circle" style="width: 80px; height: 80px; background: rgba(239, 68, 68, 0.15); border: 2px solid rgba(239, 68, 68, 0.4);">
                        <i class="bi bi-shield-slash-fill fs-1 text-danger"></i>
                    </span>
                </div>
                <h3 class="font-heading text-gold fw-bold text-uppercase mb-2" style="letter-spacing: 0.05em;">Order Limit Exceeded</h3>
                <p class="text-parchment fs-5 mb-2">Online orders cannot exceed <strong>$50.00</strong>.</p>
                <p class="text-parchment-dim mb-4" style="font-size: 14px; line-height: 1.6;">
                    Your cart subtotal is <strong class="text-danger fs-5" id="bypassBlockedTotalDisplay">$0.00</strong>.
                    Online checkout is restricted for orders with items over $50. Please reduce items in your cart to proceed.
                </p>
                <div class="d-flex flex-wrap justify-content-center gap-3">
                    <button type="button" class="btn btn-gold px-4 py-2" onclick="if(window.AZCart) window.AZCart.openDrawer()">
                        <i class="bi bi-bag me-1"></i> Edit Cart Items
                    </button>
                    <a href="{{ route('catalog') }}" class="btn btn-gold-outline px-4 py-2">
                        <i class="bi bi-arrow-left me-1"></i> Return to Catalog
                    </a>
                </div>
            </div>
        </div>

        <div class="row g-4" id="checkoutMainContainer">

            <!-- Checkout Form -->
            <div class="col-lg-7" data-aos="fade-right" data-aos-duration="750">
                <div class="luxury-card p-4 p-md-5">
                    <h4 class="font-heading text-gold mb-4 fw-bold text-uppercase" style="letter-spacing: 0.1em;">1. Customer & Delivery Info</h4>

                    <!-- Lockout Banner when order > $50 -->
                    <div id="checkoutFormLockoutBanner" class="alert alert-warning border border-warning d-none mb-4 p-3 rounded" style="background: rgba(255, 193, 7, 0.12); color: #ffc107;" role="alert">
                        <div class="d-flex align-items-start gap-3">
                            <i class="bi bi-shield-lock-fill fs-2 text-warning flex-shrink-0 mt-1"></i>
                            <div>
                                <h5 class="font-heading text-uppercase fw-bold text-warning mb-1" style="letter-spacing: 0.05em; font-size: 15px;">Checkout Details Locked (Order > $50.00)</h5>
                                <p class="mb-2 text-parchment small" style="line-height: 1.6;">
                                    Online checkout details are disabled because orders cannot exceed <strong>$50.00</strong>. Your current cart subtotal is <strong class="text-gold fs-6" id="lockoutCartTotalDisplay">$0.00</strong>.
                                </p>
                                <p class="mb-2 text-parchment-dim small" style="line-height: 1.5;">
                                    To place this order online, please reduce items in your cart to $50 or below:
                                </p>
                                <div class="d-flex flex-wrap gap-2 mt-2">
                                    <button type="button" class="btn btn-sm btn-gold-outline py-1 px-3" style="font-size: 11px;" onclick="if(window.AZCart) window.AZCart.openDrawer()">
                                        <i class="bi bi-bag me-1"></i> Edit Cart Items
                                    </button>
                                    <a href="{{ route('catalog') }}" class="btn btn-sm btn-gold-outline py-1 px-3" style="font-size: 11px;">
                                        <i class="bi bi-arrow-left me-1"></i> Browse Catalog
                                    </a>
                                    <a href="tel:9192448634" class="btn btn-sm btn-outline-warning py-1 px-3 text-decoration-none d-flex align-items-center gap-1" style="font-size: 11px;">
                                        <i class="bi bi-telephone"></i> Call: 919-244-8634
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

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
                                        <label class="form-check-label text-parchment" for="dt_delivery">Home Delivery (Free within 10 min)</label>
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
                                    <div class="p-3 border border-secondary border-opacity-25 rounded bg-dark bg-opacity-50 payment-method-card cursor-pointer" id="card_pm_cod" onclick="selectPaymentMethod('cash_on_delivery')">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="payment_method" id="pm_cod" value="cash_on_delivery" checked onchange="selectPaymentMethod('cash_on_delivery')">
                                            <label class="form-check-label text-parchment fw-semibold" for="pm_cod">
                                                💵 Cash on Delivery / Store Pickup
                                            </label>
                                        </div>
                                        <p class="text-parchment-muted small mb-0 ps-4">Pay in cash or card upon delivery to your doorstep, or at the store counter upon pickup.</p>
                                    </div>

                                    <!-- Option 2: PayPal & Online Payment -->
                                    <div class="p-3 border border-secondary border-opacity-25 rounded bg-dark bg-opacity-50 payment-method-card cursor-pointer" id="card_pm_paypal" onclick="selectPaymentMethod('paypal')">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="payment_method" id="pm_paypal" value="paypal" onchange="selectPaymentMethod('paypal')">
                                            <label class="form-check-label text-parchment fw-semibold d-flex align-items-center gap-2" for="pm_paypal">
                                                <i class="bi bi-paypal text-info fs-5"></i> Pay with PayPal / Debit or Credit Card (Online Payment)
                                            </label>
                                        </div>
                                        <p class="text-parchment-muted small mb-0 ps-4">Safe, instant, and encrypted checkout via PayPal balance, bank, or credit/debit card.</p>
                                    </div>
                                </div>
                            </div>

                            <!-- COD Submit Button Container -->
                            <div class="col-12 pt-4" id="cod-button-container">
                                <button type="submit" id="btnSubmitOrder" class="btn-gold-outline w-100 py-3 text-center">
                                    <span>Place Order (Cash on Delivery)</span>
                                </button>
                            </div>

                            <!-- PayPal Payment Container -->
                            <div class="col-12 pt-4 d-none" id="paypal-payment-section">
                                <div class="p-4 border border-gold border-opacity-50 rounded bg-dark bg-opacity-90">
                                    <div class="d-flex align-items-center justify-content-between mb-3 pb-2 border-bottom border-secondary border-opacity-25">
                                        <div class="d-flex align-items-center gap-2">
                                            <i class="bi bi-paypal fs-4 text-info"></i>
                                            <div>
                                                <div class="text-parchment fw-bold small">PayPal Express Checkout</div>
                                                <div class="text-parchment-muted" style="font-size: 11px;">
                                                    {{ ($paypalMode ?? 'sandbox') === 'sandbox' ? 'PayPal Sandbox Test Mode' : 'Live Secure PayPal Gateway' }}
                                                </div>
                                            </div>
                                        </div>
                                        <span class="badge bg-gold text-dark px-2 py-1" style="font-size: 10px;">SECURE 256-BIT</span>
                                    </div>

                                    <!-- Loading / Authorizing Spinner Overlay -->
                                    <div id="paypal-processing-spinner" class="d-none text-center py-4">
                                        <div class="spinner-border text-warning" role="status" style="width: 2.5rem; height: 2.5rem;">
                                            <span class="visually-hidden">Processing...</span>
                                        </div>
                                        <div class="text-gold fw-semibold mt-3 small" id="paypal-processing-text">
                                            Authorizing PayPal payment & securing your order...
                                        </div>
                                        <div class="text-parchment-muted mt-1" style="font-size: 11px;">
                                            Please do not refresh or close this window.
                                        </div>
                                    </div>

                                    <!-- Real PayPal Smart Payment Buttons Mount Target -->
                                    <div id="paypal-button-container" class="mt-2"></div>

                                    <div class="text-center mt-3 text-parchment-muted" style="font-size: 11px;">
                                        <i class="bi bi-shield-lock-fill text-gold me-1"></i> Pay securely using your PayPal account balance or credit/debit card.
                                    </div>
                                </div>
                            </div>

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

                    <!-- Maximum $50 Order Limit Notice -->
                    <div id="checkoutLimitWarning" class="alert alert-warning border border-warning d-none mt-3 mb-0" style="font-size: 12px; background: rgba(255, 193, 7, 0.1); color: #ffc107;" role="alert">
                        <div class="d-flex align-items-start gap-2">
                            <i class="bi bi-exclamation-triangle-fill fs-5 text-warning flex-shrink-0"></i>
                            <div>
                                <strong class="text-warning text-uppercase">Order Limit Exceeded:</strong><br>
                                <span>Online orders cannot exceed <strong>$50.00</strong>. For bulk meat, whole goat, or catering orders, please call us at <a href="tel:9192448634" class="text-warning fw-bold text-decoration-underline">919-244-8634</a>.</span>
                            </div>
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

@endsection

@push('scripts')

<script src="https://www.paypal.com/sdk/js?client-id={{ $paypalClientId ?? config('services.paypal.client_id', 'sb') }}&currency={{ $paypalCurrency ?? config('services.paypal.currency', 'USD') }}&intent=capture&enable-funding=venmo,card"></script>
<script>
    window.AZ_DELIVERY_FEE = {{ (float) ($configuredDeliveryFee ?? \App\Models\SiteSection::getValue('delivery_fee', '0.00')) }};
    window.AZ_FREE_DELIVERY_THRESHOLD = {{ (float) ($freeDeliveryThreshold ?? \App\Models\SiteSection::getValue('delivery_free_threshold', '0.00')) }};

    let paypalButtonsRendered = false;

function selectPaymentMethod(method) {
    const { subtotal, total } = getCartTotal();
    if (subtotal > 50 || total > 50) return;

    const pmCod = document.getElementById('pm_cod');
    const pmPaypal = document.getElementById('pm_paypal');
    const codContainer = document.getElementById('cod-button-container');
    const paypalSection = document.getElementById('paypal-payment-section');
    const cardCod = document.getElementById('card_pm_cod');
    const cardPaypal = document.getElementById('card_pm_paypal');

    if (method === 'paypal') {
        if (pmPaypal) pmPaypal.checked = true;
        if (codContainer) codContainer.classList.add('d-none');
        if (paypalSection) paypalSection.classList.remove('d-none');
        if (cardPaypal) {
            cardPaypal.classList.add('border-gold');
            cardPaypal.classList.remove('border-secondary');
        }
        if (cardCod) {
            cardCod.classList.remove('border-gold');
            cardCod.classList.add('border-secondary');
        }
        renderPaypalSmartButtons();
    } else {
        if (pmCod) pmCod.checked = true;
        if (codContainer) codContainer.classList.remove('d-none');
        if (paypalSection) paypalSection.classList.add('d-none');
        if (cardCod) {
            cardCod.classList.add('border-gold');
            cardCod.classList.remove('border-secondary');
        }
        if (cardPaypal) {
            cardPaypal.classList.remove('border-gold');
            cardPaypal.classList.add('border-secondary');
        }
    }
}

function showPaypalProcessing(isProcessing, message) {
    const spinner = document.getElementById('paypal-processing-spinner');
    const textEl = document.getElementById('paypal-processing-text');
    const btnContainer = document.getElementById('paypal-button-container');

    if (spinner && btnContainer) {
        if (isProcessing) {
            if (message && textEl) textEl.textContent = message;
            spinner.classList.remove('d-none');
            btnContainer.classList.add('d-none');
        } else {
            spinner.classList.add('d-none');
            btnContainer.classList.remove('d-none');
        }
    }
}

function renderPaypalSmartButtons() {
    if (paypalButtonsRendered) return;
    const target = document.getElementById('paypal-button-container');
    if (!target) return;

    if (typeof paypal === 'undefined') {
        target.innerHTML = `
            <div class="alert alert-warning small text-center my-2">
                <i class="bi bi-exclamation-triangle-fill me-1"></i> PayPal SDK is loading or blocked by browser extension. Please refresh or select Cash on Delivery.
            </div>
        `;
        return;
    }

    paypalButtonsRendered = true;
    target.innerHTML = '';

    paypal.Buttons({
        style: {
            layout: 'vertical',
            color: 'gold',
            shape: 'rect',
            label: 'paypal',
            height: 48
        },
        onClick: function(data, actions) {
            if (!validateCustomerForm()) {
                return actions.reject();
            }
            return actions.resolve();
        },
        createOrder: async function(data, actions) {
            const form = document.getElementById('checkoutOrderForm');
            const items = window.AZCart ? window.AZCart.items : [];
            const formData = form ? new FormData(form) : new FormData();
            const { total } = getCartTotal();

            // First attempt to create order through backend (if PayPal API configured)
            try {
                const res = await fetch('{{ route('checkout.paypal.create') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        items: items,
                        delivery_type: formData.get('delivery_type'),
                    })
                });

                const orderData = await res.json();
                if (orderData.success && orderData.order_id) {
                    return orderData.order_id;
                }
            } catch (e) {
                console.warn('Backend PayPal order creation fallback to client-side:', e);
            }

            // Client-side order creation using PayPal SDK
            return actions.order.create({
                purchase_units: [{
                    description: 'AZ Halal Marts Order',
                    amount: {
                        currency_code: '{{ $paypalCurrency ?? config('services.paypal.currency', 'USD') }}',
                        value: total.toFixed(2)
                    }
                }]
            });
        },
        onApprove: async function(data, actions) {
            showPaypalProcessing(true, 'Authorizing & capturing payment with PayPal...');

            try {
                // Capture the real transaction on PayPal
                const details = await actions.order.capture();

                showPaypalProcessing(true, 'Payment captured! Securing order in store...');

                const form = document.getElementById('checkoutOrderForm');
                const items = window.AZCart ? window.AZCart.items : [];
                const formData = form ? new FormData(form) : new FormData();
                const captureId = details?.purchase_units?.[0]?.payments?.captures?.[0]?.id || details?.id || data.orderID;

                const payload = {
                    customer_name: formData.get('customer_name'),
                    customer_email: formData.get('customer_email'),
                    customer_phone: formData.get('customer_phone'),
                    delivery_address: formData.get('delivery_address'),
                    city: formData.get('city'),
                    postal_code: formData.get('postal_code'),
                    delivery_type: formData.get('delivery_type'),
                    notes: formData.get('notes'),
                    paypal_order_id: data.orderID,
                    transaction_id: captureId,
                    payer_details: {
                        payer_id: details?.payer?.payer_id || data.payerID,
                        payer_email: details?.payer?.email_address || formData.get('customer_email'),
                        payer_name: ((details?.payer?.name?.given_name || '') + ' ' + (details?.payer?.name?.surname || '')).trim() || formData.get('customer_name'),
                        status: details?.status || 'COMPLETED',
                        amount: details?.purchase_units?.[0]?.amount?.value || getCartTotal().total
                    },
                    items: items
                };

                const response = await fetch('{{ route('checkout.paypal.capture') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify(payload)
                });

                const serverRes = await response.json();
                if (serverRes.success && serverRes.redirect) {
                    if (window.AZCart) window.AZCart.clear();
                    window.location.href = serverRes.redirect;
                } else {
                    alert(serverRes.message || 'Payment was captured on PayPal, but saving the order in the system failed. Please save your PayPal Transaction ID: ' + captureId);
                    showPaypalProcessing(false);
                }
            } catch (err) {
                console.error('PayPal Capture Error:', err);
                alert('An error occurred during PayPal capture: ' + (err.message || 'Unknown error'));
                showPaypalProcessing(false);
            }
        },
        onError: function(err) {
            console.error('PayPal SDK Error:', err);
            alert('PayPal window was closed or encountered an error. Please check your credentials or try again.');
            showPaypalProcessing(false);
        },
        onCancel: function(data) {
            showPaypalProcessing(false);
        }
    }).render('#paypal-button-container');
}

function getCartTotal() {
    const form = document.getElementById('checkoutOrderForm');
    let items = (window.AZCart && Array.isArray(window.AZCart.items) && window.AZCart.items.length > 0)
        ? window.AZCart.items
        : null;

    if (!items) {
        try {
            const saved = localStorage.getItem('az_halal_cart_v1');
            if (saved) {
                items = JSON.parse(saved);
            }
        } catch (e) {
            items = [];
        }
    }

    items = Array.isArray(items) ? items : [];
    let subtotal = 0;
    items.forEach(i => {
        const price = parseFloat(i.price) || 0;
        const qty = parseInt(i.quantity, 10) || 1;
        subtotal += (price * qty);
    });

    const deliveryType = form ? form.querySelector('input[name="delivery_type"]:checked')?.value : 'delivery';
    const standardFee = parseFloat(window.AZ_DELIVERY_FEE) || 0.00;
    const freeThreshold = parseFloat(window.AZ_FREE_DELIVERY_THRESHOLD) || 0.00;
    let fee = 0.00;
    if (deliveryType === 'delivery' && standardFee > 0 && subtotal > 0) {
        if (freeThreshold > 0 && subtotal >= freeThreshold) {
            fee = 0.00;
        } else {
            fee = standardFee;
        }
    }
    return { subtotal, fee, total: subtotal + fee };
}

function validateCustomerForm() {
    const form = document.getElementById('checkoutOrderForm');
    if (!form) return false;
    const name = form.querySelector('[name="customer_name"]')?.value.trim();
    const email = form.querySelector('[name="customer_email"]')?.value.trim();
    const phone = form.querySelector('[name="customer_phone"]')?.value.trim();
    const deliveryType = form.querySelector('[name="delivery_type"]:checked')?.value;
    const address = form.querySelector('[name="delivery_address"]')?.value.trim();

    if (!name) {
        alert('Please enter your full name.');
        form.querySelector('[name="customer_name"]')?.focus();
        return false;
    }
    if (!email) {
        alert('Please enter your email address.');
        form.querySelector('[name="customer_email"]')?.focus();
        return false;
    }
    if (!phone) {
        alert('Please enter your phone number.');
        form.querySelector('[name="customer_phone"]')?.focus();
        return false;
    }

    if (deliveryType === 'delivery' && !address) {
        alert('Please enter your delivery street address.');
        form.querySelector('[name="delivery_address"]')?.focus();
        return false;
    }

    let items = (window.AZCart && Array.isArray(window.AZCart.items) && window.AZCart.items.length > 0)
        ? window.AZCart.items
        : null;
    if (!items) {
        try {
            const saved = localStorage.getItem('az_halal_cart_v1');
            if (saved) items = JSON.parse(saved);
        } catch (e) {
            items = [];
        }
    }
    items = Array.isArray(items) ? items : [];
    if (items.length === 0) {
        alert('Your cart is empty. Please add items to your cart before proceeding.');
        return false;
    }

    const { subtotal } = getCartTotal();
    if (subtotal > 50) {
        alert('Online orders cannot exceed $50.00. For wholesale or orders over $50, please call us directly at 919-244-8634.');
        return false;
    }

    return true;
}

document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('checkoutOrderForm');
    const itemsList = document.getElementById('checkoutItemsList');
    const subtotalEl = document.getElementById('checkoutSubtotal');
    const totalEl = document.getElementById('checkoutTotalAmount');
    const deliveryFeeEl = document.getElementById('checkoutDeliveryFee');
    const btnSubmit = document.getElementById('btnSubmitOrder');

    function renderCheckoutItems() {
        let items = (window.AZCart && Array.isArray(window.AZCart.items))
            ? window.AZCart.items
            : null;
        if (!items || items.length === 0) {
            try {
                const saved = localStorage.getItem('az_halal_cart_v1');
                if (saved) items = JSON.parse(saved);
            } catch (e) {
                items = [];
            }
        }
        items = Array.isArray(items) ? items : [];

        const { subtotal, fee, total } = getCartTotal();
        const isOverLimit = (subtotal > 50.00);

        const mainContainer = document.getElementById('checkoutMainContainer');
        const lockoutScreen = document.getElementById('checkoutBypassLockoutScreen');
        const bypassTotalEl = document.getElementById('bypassBlockedTotalDisplay');
        const limitWarning = document.getElementById('checkoutLimitWarning');
        const lockoutBanner = document.getElementById('checkoutFormLockoutBanner');
        const lockoutTotalDisplay = document.getElementById('lockoutCartTotalDisplay');

        if (isOverLimit) {
            if (mainContainer) mainContainer.classList.add('d-none');
            if (lockoutScreen) lockoutScreen.classList.remove('d-none');
            if (bypassTotalEl) bypassTotalEl.textContent = '$' + subtotal.toFixed(2);
            if (limitWarning) limitWarning.classList.remove('d-none');
            if (lockoutBanner) lockoutBanner.classList.remove('d-none');
            if (lockoutTotalDisplay) lockoutTotalDisplay.textContent = '$' + subtotal.toFixed(2);

            if (form) {
                form.style.opacity = '0.4';
                form.style.pointerEvents = 'none';
                form.querySelectorAll('input, textarea, select, button').forEach(el => {
                    el.disabled = true;
                });
            }

            if (btnSubmit) {
                btnSubmit.disabled = true;
                btnSubmit.classList.add('opacity-50');
                btnSubmit.innerHTML = '<span>Order Exceeds $50 Max Limit</span>';
            }
        } else {
            if (mainContainer) mainContainer.classList.remove('d-none');
            if (lockoutScreen) lockoutScreen.classList.add('d-none');
            if (limitWarning) limitWarning.classList.add('d-none');
            if (lockoutBanner) lockoutBanner.classList.add('d-none');

            if (form) {
                form.style.opacity = '1';
                form.style.pointerEvents = 'auto';
                form.querySelectorAll('input, textarea, select, button').forEach(el => {
                    el.disabled = false;
                });
            }

            if (btnSubmit) {
                btnSubmit.disabled = false;
                btnSubmit.classList.remove('opacity-50');
                btnSubmit.innerHTML = '<span>Place Order (Cash on Delivery)</span>';
            }
        }

        if (itemsList) {
            if (items.length === 0) {
                itemsList.innerHTML = '<p class="text-parchment-muted small text-center my-3">No items in your cart. <a href="{{ route('catalog') }}" class="text-gold">Browse Catalog</a></p>';
                if (btnSubmit) btnSubmit.disabled = true;
            } else {
                itemsList.innerHTML = items.map(i => {
                    const price = parseFloat(i.price) || 0;
                    const qty = parseInt(i.quantity, 10) || 1;
                    const itemTotal = price * qty;
                    const itemImg = (i.img && typeof i.img === 'string') ? i.img.replace(/^http:\/\//i, 'https://') : '';
                    return `
                        <div class="d-flex align-items-center justify-content-between gap-2 pb-2 border-bottom border-secondary border-opacity-15">
                            <div class="d-flex align-items-center gap-2 min-w-0">
                                ${itemImg ? `<img src="${itemImg}" style="width:36px;height:36px;object-fit:cover;border-radius:3px;">` : ''}
                                <div class="min-w-0">
                                    <div class="text-truncate small fw-semibold text-parchment">${i.name}</div>
                                    <span class="text-parchment-muted" style="font-size:11px;">Qty: ${qty} × $${price.toFixed(2)}</span>
                                </div>
                            </div>
                            <span class="font-heading text-gold fw-bold small flex-shrink-0">$${itemTotal.toFixed(2)}</span>
                        </div>
                    `;
                }).join('');
            }
        }

        if (subtotalEl) subtotalEl.textContent = '$' + subtotal.toFixed(2);
        if (deliveryFeeEl) deliveryFeeEl.textContent = fee > 0 ? '$' + fee.toFixed(2) : 'FREE';
        if (totalEl) totalEl.textContent = '$' + total.toFixed(2);
    }

    // Initial render
    renderCheckoutItems();

    // Show alert once on initial load if over limit (NO REDIRECT)
    const { subtotal: initSubtotal } = getCartTotal();
    if (initSubtotal > 50) {
        const alertOptions = {
            title: 'Order Limit Exceeded',
            message: `Online checkout is limited to orders up to <strong>$50.00</strong>.<br><br>Your cart subtotal is <span class="text-gold fw-bold">$${initSubtotal.toFixed(2)}</span>.<br><br>Please adjust your cart items or reduce quantities to proceed with checkout.`,
            icon: 'shield-lock',
            confirmText: 'Understood'
        };
        if (typeof window.showAZAlert === 'function') {
            window.showAZAlert(alertOptions);
        }
    }

    // Dynamically update checkout whenever cart items change in drawer or storage
    window.addEventListener('az-cart-updated', () => {
        renderCheckoutItems();
    });
    window.addEventListener('storage', (e) => {
        if (e.key === 'az_halal_cart_v1') {
            renderCheckoutItems();
        }
    });

    // Delivery type change updates summary
    const dtInputs = document.querySelectorAll('input[name="delivery_type"]');
    dtInputs.forEach(input => {
        input.addEventListener('change', renderCheckoutItems);
    });

    // Standard Cash on Delivery Form Submit
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
                    btnSubmit.innerHTML = '<span>Place Order (Cash on Delivery)</span>';
                    btnSubmit.disabled = false;
                }
            } catch (err) {
                console.error(err);
                alert('An error occurred while placing your order. Please try again.');
                btnSubmit.innerHTML = '<span>Place Order (Cash on Delivery)</span>';
                btnSubmit.disabled = false;
            }
        });
    }
});

</script>
@endpush
