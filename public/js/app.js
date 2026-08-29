// AZ Halal Marts Application JS
(function () {
  'use strict';

  // Toast helper
  function showToast(msg) {
    const toast = document.getElementById('azToast');
    if (!toast) return;
    toast.textContent = msg;
    toast.classList.add('show');
    setTimeout(() => {
      toast.classList.remove('show');
    }, 2200);
  }

  // Cart State Manager
  const Cart = {
    key: 'az_halal_cart_v1',
    items: [],

    init() {
      try {
        const saved = localStorage.getItem(this.key);
        if (saved) this.items = JSON.parse(saved);
      } catch (e) {
        this.items = [];
      }
      this.render();
      this.bindEvents();
    },

    save() {
      localStorage.setItem(this.key, JSON.stringify(this.items));
      this.render();
    },

    addItem(product, qty = 1) {
      const existing = this.items.find(i => i.id === product.id);
      if (existing) {
        existing.quantity += qty;
      } else {
        this.items.push({
          id: product.id,
          name: product.name,
          price: parseFloat(product.price) || 0,
          sku: product.sku || '',
          img: product.img || '',
          quantity: qty,
        });
      }
      this.save();
      showToast(`Added ${product.name} to cart`);
    },

    updateQuantity(id, delta) {
      const item = this.items.find(i => i.id === id);
      if (!item) return;
      item.quantity += delta;
      if (item.quantity <= 0) {
        this.items = this.items.filter(i => i.id !== id);
      }
      this.save();
    },

    removeItem(id) {
      this.items = this.items.filter(i => i.id !== id);
      this.save();
      showToast('Item removed from cart');
    },

    clear() {
      this.items = [];
      this.save();
      showToast('Cart cleared');
    },

    getItemQty(id) {
      const item = this.items.find(i => i.id === id);
      return item ? item.quantity : 0;
    },

    getCount() {
      return this.items.reduce((sum, item) => sum + item.quantity, 0);
    },

    getSubtotal() {
      return this.items.reduce((sum, item) => sum + (item.price * item.quantity), 0);
    },

    render() {
      const count = this.getCount();
      const subtotal = this.getSubtotal();

      // Update navbar badges
      document.querySelectorAll('.cart-badge-count').forEach(el => {
        el.textContent = count;
        el.style.display = count > 0 ? 'flex' : 'none';
      });

      // Update cart drawer header count
      const drawerCountHeader = document.getElementById('cartDrawerCountHeader');
      if (drawerCountHeader) {
        drawerCountHeader.textContent = `(${count})`;
      }

      // Update product cards action buttons across the page
      document.querySelectorAll('.card-action-container').forEach(container => {
        const id = parseInt(container.dataset.id, 10);
        const name = container.dataset.name;
        const price = parseFloat(container.dataset.price);
        const sku = container.dataset.sku;
        const img = container.dataset.img;
        const qty = this.getItemQty(id);

        if (qty > 0) {
          container.innerHTML = `
            <div class="card-qty-control">
              <button type="button" class="btn-card-qty-dec" data-id="${id}" aria-label="Decrease quantity">−</button>
              <span class="qty-value">${qty}</span>
              <button type="button" class="btn-card-qty-inc" data-id="${id}" aria-label="Increase quantity">+</button>
            </div>
          `;
        } else {
          container.innerHTML = `
            <button type="button" class="btn-gold-outline py-2 px-3 btn-add-to-cart" 
              data-id="${id}" 
              data-name="${name}" 
              data-price="${price}" 
              data-sku="${sku}" 
              data-img="${img}"
              style="font-size: 10px; letter-spacing: 0.2em;">
              <span>Add to Cart</span>
            </button>
          `;
        }
      });

      // Update drawer body
      const container = document.getElementById('cartItemsContainer');
      const emptyState = document.getElementById('cartEmptyState');
      const footerState = document.getElementById('cartFooterState');
      const subtotalEl = document.getElementById('cartSubtotalAmount');

      if (!container) return;

      if (this.items.length === 0) {
        if (emptyState) emptyState.style.display = 'block';
        if (footerState) footerState.style.display = 'none';
        container.innerHTML = '';
      } else {
        if (emptyState) emptyState.style.display = 'none';
        if (footerState) footerState.style.display = 'block';
        if (subtotalEl) subtotalEl.textContent = '$' + subtotal.toFixed(2);

        container.innerHTML = this.items.map(item => `
          <div class="cart-item-row">
            <img src="${item.img}" alt="${item.name}" style="width:64px;height:64px;object-fit:cover;border:1px solid rgba(212,175,55,0.25);" class="flex-shrink-0">
            <div class="flex-grow-1 min-w-0">
              <h6 class="font-heading text-gold mb-1 text-uppercase text-truncate" style="font-size:13px;letter-spacing:0.05em;">${item.name}</h6>
              <div class="text-parchment-muted" style="font-size:12px;">$${item.price.toFixed(2)} each</div>
              <div class="d-flex align-items-center justify-content-between mt-2">
                <div class="card-qty-control">
                  <button type="button" class="btn-qty-dec" data-id="${item.id}" aria-label="Decrease quantity">−</button>
                  <span class="qty-value">${item.quantity}</span>
                  <button type="button" class="btn-qty-inc" data-id="${item.id}" aria-label="Increase quantity">+</button>
                </div>
                <button type="button" class="btn btn-link text-parchment-muted p-0 btn-remove-item" data-id="${item.id}" style="font-size:12px;text-decoration:none;">
                  <i class="bi bi-trash3"></i> Remove
                </button>
              </div>
            </div>
          </div>
        `).join('');
      }
    },

    openDrawer() {
      const drawerEl = document.getElementById('cartOffcanvas');
      if (drawerEl && window.bootstrap) {
        const bsOffcanvas = bootstrap.Offcanvas.getOrCreateInstance(drawerEl);
        bsOffcanvas.show();
      }
    },

    bindEvents() {
      // Add to cart click
      document.addEventListener('click', e => {
        const btn = e.target.closest('.btn-add-to-cart');
        if (btn) {
          const product = {
            id: parseInt(btn.dataset.id, 10),
            name: btn.dataset.name,
            price: parseFloat(btn.dataset.price),
            sku: btn.dataset.sku,
            img: btn.dataset.img,
          };
          this.addItem(product);
        }

        // Qty modifier buttons (drawer & card)
        const btnInc = e.target.closest('.btn-qty-inc, .btn-card-qty-inc');
        if (btnInc) {
          this.updateQuantity(parseInt(btnInc.dataset.id, 10), 1);
        }

        const btnDec = e.target.closest('.btn-qty-dec, .btn-card-qty-dec');
        if (btnDec) {
          this.updateQuantity(parseInt(btnDec.dataset.id, 10), -1);
        }

        const btnRemove = e.target.closest('.btn-remove-item');
        if (btnRemove) {
          this.removeItem(parseInt(btnRemove.dataset.id, 10));
        }

        const btnClear = e.target.closest('#btnClearCart');
        if (btnClear) {
          this.clear();
        }

        const btnWhatsAppCheckout = e.target.closest('#btnWhatsAppCheckout');
        if (btnWhatsAppCheckout) {
          this.generateWhatsAppOrder();
        }
      });
    },

    generateWhatsAppOrder() {
      if (this.items.length === 0) return;
      let text = "Salam AZ Halal Marts, I would like to place an order:\n\n";
      this.items.forEach(i => {
        text += `• ${i.name} (x${i.quantity}) - $${(i.price * i.quantity).toFixed(2)}\n`;
      });
      text += `\nTotal: $${this.getSubtotal().toFixed(2)}\n\nPlease confirm delivery / pickup availability.`;
      const url = "https://wa.me/19192448634?text=" + encodeURIComponent(text);
      window.open(url, '_blank');
    }
  };

  // Sticky Navbar scroll trigger
  function handleNavScroll() {
    const nav = document.querySelector('.site-navbar');
    if (nav) {
      if (window.scrollY > 40) {
        nav.classList.add('scrolled');
      } else {
        nav.classList.remove('scrolled');
      }
    }
  }

  window.addEventListener('scroll', handleNavScroll);

  // Global Init
  document.addEventListener('DOMContentLoaded', () => {
    handleNavScroll();
    Cart.init();

    // Catalog & Products filter and search
    const searchInput = document.getElementById('catalogSearchInput');
    const filterButtons = document.querySelectorAll('.catalog-filter-btn');
    const productCards = document.querySelectorAll('.catalog-product-item');
    const noResults = document.getElementById('catalogNoResults');

    let currentCategory = 'All';
    let searchQuery = '';

    function filterCatalog() {
      let visibleCount = 0;
      productCards.forEach(card => {
        const cat = card.dataset.category || '';
        const name = (card.dataset.name || '').toLowerCase();
        const desc = (card.dataset.desc || '').toLowerCase();

        const matchCat = (currentCategory === 'All' || cat.toLowerCase() === currentCategory.toLowerCase());
        const matchSearch = (!searchQuery || name.includes(searchQuery) || desc.includes(searchQuery));

        if (matchCat && matchSearch) {
          card.style.display = 'block';
          visibleCount++;
        } else {
          card.style.display = 'none';
        }
      });

      if (noResults) {
        noResults.style.display = (visibleCount === 0) ? 'block' : 'none';
      }
    }

    if (filterButtons.length > 0) {
      filterButtons.forEach(btn => {
        btn.addEventListener('click', () => {
          filterButtons.forEach(b => b.classList.remove('active'));
          btn.classList.add('active');
          currentCategory = btn.dataset.filter || 'All';
          filterCatalog();
        });
      });
    }

    if (searchInput) {
      searchInput.addEventListener('input', (e) => {
        searchQuery = e.target.value.trim().toLowerCase();
        filterCatalog();
      });
    }

    // Gallery Lightbox Modal
    const galleryItems = document.querySelectorAll('.gallery-item');
    const lightboxModalEl = document.getElementById('galleryLightboxModal');
    const lightboxImg = document.getElementById('lightboxImage');
    const lightboxCaption = document.getElementById('lightboxCaption');
    const lightboxTag = document.getElementById('lightboxTag');

    if (galleryItems.length > 0 && lightboxModalEl) {
      galleryItems.forEach(item => {
        item.addEventListener('click', () => {
          const src = item.dataset.src;
          const caption = item.dataset.caption;
          const tag = item.dataset.tag;

          if (lightboxImg) lightboxImg.src = src;
          if (lightboxCaption) lightboxCaption.textContent = caption;
          if (lightboxTag) lightboxTag.textContent = tag;

          const bsModal = bootstrap.Modal.getOrCreateInstance(lightboxModalEl);
          bsModal.show();
        });
      });
    }

    // Gallery Filter Tabs
    const galleryFilterBtns = document.querySelectorAll('.gallery-filter-btn');
    if (galleryFilterBtns.length > 0) {
      galleryFilterBtns.forEach(btn => {
        btn.addEventListener('click', () => {
          galleryFilterBtns.forEach(b => b.classList.remove('active'));
          btn.classList.add('active');
          const filter = btn.dataset.tag;
          galleryItems.forEach(item => {
            if (filter === 'All' || item.dataset.tag === filter) {
              item.style.display = 'block';
            } else {
              item.style.display = 'none';
            }
          });
        });
      });
    }

    // Contact Wholesale Inquiry Type Selector
    const inquiryTypeBtns = document.querySelectorAll('.inquiry-type-btn');
    const inquiryTypeInput = document.getElementById('inquiryTypeInput');
    if (inquiryTypeBtns.length > 0 && inquiryTypeInput) {
      inquiryTypeBtns.forEach(btn => {
        btn.addEventListener('click', () => {
          inquiryTypeBtns.forEach(b => b.classList.remove('active'));
          btn.classList.add('active');
          inquiryTypeInput.value = btn.dataset.type;
        });
      });
    }
  });

  window.AZCart = Cart;
})();
