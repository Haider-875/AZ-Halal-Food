<!-- Custom Luxury Halal Artisan Alert / Dialog Modal -->
<div id="azThemeAlertModal" class="az-alert-backdrop" aria-hidden="true" role="dialog" aria-modal="true">
    <div class="az-alert-container">
        <div class="az-alert-card">
            <!-- Top Gold Shimmer Bar -->
            <div class="az-alert-shimmer-bar"></div>

            <!-- Close Button -->
            <button type="button" class="az-alert-close-btn" id="azAlertCloseBtn" aria-label="Close dialog">
                <i class="bi bi-x-lg"></i>
            </button>

            <!-- Dialog Content -->
            <div class="az-alert-body text-center">
                <!-- Icon Header -->
                <div class="az-alert-icon-wrapper az-alert-icon-warning" id="azAlertIconWrapper">
                    <i class="bi bi-shield-exclamation text-warning" id="azAlertIcon"></i>
                </div>

                <!-- Title -->
                <h4 class="font-heading text-gold text-uppercase fw-bold mb-3" id="azAlertTitle" style="letter-spacing: 0.08em; font-size: 1.25rem;">
                    Notice
                </h4>

                <!-- Message -->
                <div class="az-alert-message mb-4" id="azAlertMessage" style="font-size: 0.95rem; line-height: 1.65;">
                </div>

                <!-- Action Buttons -->
                <div class="d-flex justify-content-center align-items-center gap-2 pt-2" id="azAlertButtons">
                    <button type="button" class="btn-gold px-4 py-2" id="azAlertConfirmBtn">
                        <span>Understood</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
window.showAZAlert = function(options) {
    return new Promise((resolve) => {
        let title = 'Notice';
        let message = '';
        let iconType = 'warning';
        let confirmText = 'Understood';

        if (typeof options === 'string') {
            message = options;
            const lower = message.toLowerCase();
            if (message.includes('$50') || lower.includes('limit') || lower.includes('exceed')) {
                title = 'Order Limit Exceeded';
                iconType = 'shield-lock';
            } else if (lower.includes('error') || lower.includes('failed') || lower.includes('unable')) {
                title = 'Notice';
                iconType = 'error';
            } else if (lower.includes('success') || lower.includes('placed') || lower.includes('confirmed')) {
                title = 'Success';
                iconType = 'success';
            } else {
                title = 'Notice';
                iconType = 'info';
            }
        } else if (typeof options === 'object' && options !== null) {
            title = options.title || title;
            message = options.message || '';
            iconType = options.icon || iconType;
            confirmText = options.confirmText || confirmText;
        }

        const modal = document.getElementById('azThemeAlertModal');
        if (!modal) {
            if (typeof window._nativeAlert === 'function') {
                window._nativeAlert(message);
            }
            resolve(true);
            return;
        }

        const titleEl = document.getElementById('azAlertTitle');
        const messageEl = document.getElementById('azAlertMessage');
        const iconWrapper = document.getElementById('azAlertIconWrapper');
        const iconEl = document.getElementById('azAlertIcon');
        const confirmBtn = document.getElementById('azAlertConfirmBtn');
        const closeBtn = document.getElementById('azAlertCloseBtn');

        if (titleEl) titleEl.textContent = title;
        if (messageEl) {
            messageEl.innerHTML = message.replace(/\n/g, '<br>');
        }

        if (iconWrapper && iconEl) {
            iconWrapper.className = 'az-alert-icon-wrapper';
            if (iconType === 'error') {
                iconWrapper.classList.add('az-alert-icon-error');
                iconEl.className = 'bi bi-x-circle-fill text-danger';
            } else if (iconType === 'success') {
                iconWrapper.classList.add('az-alert-icon-success');
                iconEl.className = 'bi bi-check-circle-fill text-success';
            } else if (iconType === 'shield-lock') {
                iconWrapper.classList.add('az-alert-icon-warning');
                iconEl.className = 'bi bi-shield-lock-fill text-warning';
            } else if (iconType === 'warning') {
                iconWrapper.classList.add('az-alert-icon-warning');
                iconEl.className = 'bi bi-shield-exclamation text-warning';
            } else {
                iconWrapper.classList.add('az-alert-icon-info');
                iconEl.className = 'bi bi-info-circle-fill text-gold';
            }
        }

        if (confirmBtn) {
            confirmBtn.innerHTML = `<span>${confirmText}</span>`;
        }

        // Show modal with animation
        modal.style.display = 'flex';
        void modal.offsetWidth; // force reflow
        modal.classList.add('az-alert-active');
        modal.setAttribute('aria-hidden', 'false');

        let isResolved = false;

        function cleanup(result) {
            if (isResolved) return;
            isResolved = true;
            modal.classList.remove('az-alert-active');
            modal.setAttribute('aria-hidden', 'true');
            setTimeout(() => {
                modal.style.display = 'none';
            }, 300);

            confirmBtn.removeEventListener('click', onConfirm);
            if (closeBtn) closeBtn.removeEventListener('click', onCancel);
            modal.removeEventListener('click', onBackdropClick);
            document.removeEventListener('keydown', onKeyDown);
            resolve(result);
        }

        function onConfirm() { cleanup(true); }
        function onCancel() { cleanup(false); }
        function onBackdropClick(e) {
            if (e.target === modal) cleanup(false);
        }
        function onKeyDown(e) {
            if (e.key === 'Escape') cleanup(false);
            if (e.key === 'Enter') cleanup(true);
        }

        confirmBtn.addEventListener('click', onConfirm);
        if (closeBtn) closeBtn.addEventListener('click', onCancel);
        modal.addEventListener('click', onBackdropClick);
        document.addEventListener('keydown', onKeyDown);

        setTimeout(() => {
            if (confirmBtn) confirmBtn.focus();
        }, 50);
    });
};

// Seamless drop-in: override browser alert() so every alert uses the luxury theme modal!
if (window.alert !== window.showAZAlert) {
    window._nativeAlert = window.alert;
    window.alert = function(msg) {
        return window.showAZAlert(msg);
    };
}
</script>

