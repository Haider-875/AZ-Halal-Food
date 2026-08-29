<div class="offcanvas offcanvas-end cart-offcanvas" tabindex="-1" id="cartOffcanvas" aria-labelledby="cartOffcanvasLabel">
    <div class="offcanvas-header">
        <div class="d-flex align-items-center gap-2">
            <i class="bi bi-bag text-gold fs-5"></i>
            <h5 class="offcanvas-title font-heading text-uppercase text-parchment fw-bold mb-0" id="cartOffcanvasLabel" style="letter-spacing: 0.1em; font-size: 1.1rem;">
                Your Cart <span id="cartDrawerCountHeader" class="text-gold" style="font-size: 12px; font-weight: normal;">(0)</span>
            </h5>
        </div>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    
    <div class="offcanvas-body d-flex flex-column justify-content-between">
        
        <!-- Empty State -->
        <div id="cartEmptyState" class="text-center my-auto py-5" style="display: none;">
            <i class="bi bi-bag text-gold opacity-50" style="font-size: 3rem;"></i>
            <p class="text-parchment-dim mt-3 mb-4">Your cart is empty</p>
            <a href="{{ route('catalog') }}" class="btn-gold-outline" data-bs-dismiss="offcanvas">
                <span>Browse Catalog</span>
            </a>
        </div>

        <!-- Populated Cart Items -->
        <div id="cartItemsContainer" class="flex-grow-1 overflow-y-auto pe-1"></div>

        <!-- Drawer Footer: Subtotal & Actions -->
        <div id="cartFooterState" class="pt-4 border-top border-secondary border-opacity-25" style="display: none;">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <span class="text-uppercase small tracking-wider text-parchment-dim">Subtotal</span>
                <span id="cartSubtotalAmount" class="font-heading fs-5 fw-bold text-gold">$0.00</span>
            </div>
            <p class="text-parchment-muted small mb-3" style="font-size: 11px;">
                Free delivery within 10 miles. Call or WhatsApp for real-time delivery confirmation.
            </p>

            <div class="d-flex flex-column gap-2">
                <!-- Direct Checkout Link -->
                <a href="{{ route('checkout') }}" class="btn-gold-outline w-100 text-center text-decoration-none">
                    <span>Proceed to Checkout</span>
                </a>
                <!-- WhatsApp Quick Order -->
                <button type="button" id="btnWhatsAppCheckout" class="btn btn-outline-success w-100 py-2 d-flex align-items-center justify-content-center gap-2 fw-bold" style="font-size: 11px; letter-spacing: 0.15em; text-transform: uppercase;">
                    <i class="bi bi-whatsapp"></i> Order Via WhatsApp
                </button>
                <!-- Clear Cart -->
                <button type="button" id="btnClearCart" class="btn btn-link text-parchment-muted small text-decoration-none py-1">
                    Clear Cart
                </button>
            </div>
        </div>

    </div>
</div>

<!-- Global Toast Notification Container -->
<div id="azToast" class="az-toast">Item added to cart</div>
