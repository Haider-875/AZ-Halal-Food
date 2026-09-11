<footer style="background-color: var(--obsidian-dark); border-top: 1px solid var(--border-gold);" class="mt-auto overflow-hidden">
    <div class="container-xl py-5">
        <div class="row g-4 mb-5">

            <!-- Brand Column -->
            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-duration="700" data-aos-delay="50">
                <div class="mb-3">
                    <span class="font-heading font-black text-gold text-uppercase fw-bold fs-4" style="letter-spacing: 0.2em;">AZ Halal Marts</span>
                </div>
                <p class="text-parchment-dim small pe-lg-4 mb-4" style="line-height: 1.8;">
                    Premium halal butchery & groceries. Serving the Cary–Triangle community with excellence, fresh Zabiha cuts, exotic South Asian seafood, and seasonal Royal Pakistani mangoes.
                </p>
                <div class="d-flex gap-3">
                    <a href="https://instagram.com/azhalalmarts" target="_blank" rel="noopener noreferrer" class="text-parchment-muted fs-5 text-decoration-none" style="transition: color 0.3s;" onmouseover="this.style.color='#D4AF37'" onmouseout="this.style.color='rgba(245,240,232,0.45)'">
                        <i class="bi bi-instagram"></i>
                    </a>
                    <a href="https://facebook.com/AZHALALMARTS" target="_blank" rel="noopener noreferrer" class="text-parchment-muted fs-5 text-decoration-none" style="transition: color 0.3s;" onmouseover="this.style.color='#D4AF37'" onmouseout="this.style.color='rgba(245,240,232,0.45)'">
                        <i class="bi bi-facebook"></i>
                    </a>
                    <a href="https://wa.me/19192448634" target="_blank" rel="noopener noreferrer" class="text-parchment-muted fs-5 text-decoration-none" style="transition: color 0.3s;" onmouseover="this.style.color='#D4AF37'" onmouseout="this.style.color='rgba(245,240,232,0.45)'">
                        <i class="bi bi-whatsapp"></i>
                    </a>
                </div>
            </div>

            <!-- Quick Navigation -->
            <div class="col-lg-2 col-md-6" data-aos="fade-up" data-aos-delay="100">
                <h6 class="text-gold text-uppercase small fw-bold mb-4" style="letter-spacing: 0.25em;">Navigate</h6>
                <ul class="list-unstyled d-flex flex-column gap-2 small">
                    <li><a href="{{ route('home') }}" class="text-parchment-dim text-decoration-none hover-gold">Home</a></li>
                    <li><a href="{{ route('about') }}" class="text-parchment-dim text-decoration-none hover-gold">About Us</a></li>
                    <li><a href="{{ route('products') }}" class="text-parchment-dim text-decoration-none hover-gold">Products & Pricing</a></li>
                    <li><a href="{{ route('catalog') }}" class="text-parchment-dim text-decoration-none hover-gold">Catalog</a></li>
                    <li><a href="{{ route('gallery') }}" class="text-parchment-dim text-decoration-none hover-gold">Gallery</a></li>
                    <li><a href="{{ route('contact') }}" class="text-parchment-dim text-decoration-none hover-gold">Contact</a></li>
                </ul>
            </div>

            <!-- Offerings -->
            <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="150">
                <h6 class="text-gold text-uppercase small fw-bold mb-4" style="letter-spacing: 0.25em;">Our Specialties</h6>
                <ul class="list-unstyled d-flex flex-column gap-2 small">
                    <li class="text-parchment-dim">100% Hand-Slaughtered Zabiha Beef</li>
                    <li class="text-parchment-dim">Fresh Whole & Cut Goat / Lamb</li>
                    <li class="text-parchment-dim">Rohu, Katla & Hilsa (Ilish) Seafood</li>
                    <li class="text-parchment-dim">Royal Pakistani Mangoes (Chaunsa & Sindhri)</li>
                    <li class="text-parchment-dim">Aged Basmati Rice & Specialty Spices</li>
                    <li class="text-parchment-dim">Wholesale & Restaurant Supply</li>
                </ul>
            </div>

            <!-- Contact & Hours Info -->
            <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="200">
                <h6 class="text-gold text-uppercase small fw-bold mb-4" style="letter-spacing: 0.25em;">Store Info</h6>
                <p class="text-parchment-dim small mb-2">
                    <i class="bi bi-geo-alt text-gold me-2"></i> 716 Slash Pine Dr, Cary, NC 27519
                </p>
                <p class="text-parchment-dim small mb-2">
                    <i class="bi bi-telephone text-gold me-2"></i> <a href="tel:9192448634" class="text-parchment-dim text-decoration-none">919-244-8634</a>
                </p>
                <p class="text-parchment-dim small mb-3">
                    <i class="bi bi-telephone text-gold me-2"></i> <a href="tel:9193441125" class="text-parchment-dim text-decoration-none">919-344-1125</a>
                </p>
                <p class="text-parchment-muted small">
                    <strong class="text-parchment">Hours:</strong> Mon–Sun: 10:00 AM – 8:00 PM (Fri–Sat until 8:30 PM)
                </p>
            </div>

        </div>

        <!-- Copyright Sub-footer -->
        <div class="pt-4 border-top d-flex flex-column flex-md-row align-items-center justify-content-between gap-3 small text-parchment-muted" style="border-color: rgba(212,175,55,0.15) !important;">
            <span>© {{ date('Y') }} Copyright  Az Halal all rights reserved. Designed By <a class="link fw-bold"  href="https://azorasolution.com/">Azora Solutions.</a></span>
            <span class="text-gold-light opacity-75">716 Slash Pine Dr, West Cary, NC · 100% Zabiha Halal Certified</span>
        </div>
    </div>
</footer>


