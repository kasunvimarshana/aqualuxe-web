// Accessible Mini Cart Drawer Toggle
(() => {
  const drawer = document.getElementById('alx-mini-cart');
  if (!drawer) return;

  const panel = drawer.querySelector('.alx-mini-cart__panel');
  const live = drawer.querySelector('[aria-live]');
  const media = ('matchMedia' in window) ? window.matchMedia('(prefers-reduced-motion: reduce)') : null;
  const prefersReduced = () => !!(media && media.matches);
  let lastTrigger = null;
  let announcedUnlock = false;

  const getFocusable = () => panel.querySelectorAll(
    'a[href], button:not([disabled]), input:not([disabled]), select:not([disabled]), textarea:not([disabled]), [tabindex]:not([tabindex="-1"])'
  );

  const isOpen = () => !drawer.classList.contains('hidden');

  const open = (trigger) => {
    lastTrigger = trigger || lastTrigger;
    drawer.classList.remove('hidden');
    drawer.setAttribute('aria-hidden', 'false');
    if (lastTrigger) lastTrigger.setAttribute('aria-expanded', 'true');
    document.body.classList.add('overflow-hidden');
    panel.classList.remove('translate-x-full');
    const focusables = getFocusable();
    const toFocus = focusables[0] || panel;
    if (toFocus) setTimeout(() => toFocus.focus({ preventScroll: true }), 0);
  };

  const close = () => {
    panel.classList.add('translate-x-full');
    if (prefersReduced()) {
      drawer.classList.add('hidden');
    } else {
      // Delay hiding the overlay to allow the panel to slide out
      setTimeout(() => drawer.classList.add('hidden'), 300);
    }
    drawer.setAttribute('aria-hidden', 'true');
    if (lastTrigger) lastTrigger.setAttribute('aria-expanded', 'false');
    document.body.classList.remove('overflow-hidden');
    if (lastTrigger) setTimeout(() => lastTrigger.focus({ preventScroll: true }), 0);
  };

  // Click delegation for open/close
  document.addEventListener('click', (e) => {
    const toggle = e.target.closest('[data-minicart-toggle]');
    if (toggle) {
      // Only intercept an unmodified primary click; let modified clicks navigate normally
      const isPrimary = e.button === 0;
      const modified = e.metaKey || e.ctrlKey || e.shiftKey || e.altKey;
      if (isPrimary && !modified) {
        e.preventDefault();
        open(toggle);
      }
      return;
    }
    if (e.target.closest('[data-minicart-close]')) {
      e.preventDefault();
      if (isOpen()) close();
    }
  });

  // Keyboard handling: ESC to close, basic focus trap
  document.addEventListener('keydown', (e) => {
    if (!isOpen()) return;
    if (e.key === 'Escape') {
      e.preventDefault();
      close();
      return;
    }
    if (e.key === 'Tab') {
      const focusables = Array.from(getFocusable());
      if (!focusables.length) return;
      const first = focusables[0];
      const last = focusables[focusables.length - 1];
      if (e.shiftKey && document.activeElement === first) {
        e.preventDefault();
        last.focus();
      } else if (!e.shiftKey && document.activeElement === last) {
        e.preventDefault();
        first.focus();
      }
    }
  });

  // Auto-open on WooCommerce AJAX add-to-cart
  if (window.jQuery && jQuery.fn && jQuery(document).on) {
    jQuery(document.body).on('added_to_cart', (_evt, _fragments, _hash, $button) => {
      if (live) live.textContent = 'Item added to cart';
      open($button && $button[0] ? $button[0] : undefined);
      // Progress may update after fragments refresh; check shortly
      setTimeout(checkProgress, 250);
    });
    // When fragments refresh, check the progress state
    jQuery(document.body).on('wc_fragments_refreshed', checkProgress);
  } else {
    // Fallback: watch for mini-cart content changes and open if it updates shortly after a click on add-to-cart buttons
    const content = drawer.querySelector('.alx-mini-cart__content');
    if (content && 'MutationObserver' in window) {
      let recentAdd = false;
      document.addEventListener('click', (e) => {
        if (e.target.closest('.add_to_cart_button')) recentAdd = true;
      }, true);
      const mo = new MutationObserver(() => {
        if (recentAdd) {
          recentAdd = false;
          if (live) live.textContent = 'Item added to cart';
          open();
        }
        // Also check progress whenever content changes
        checkProgress();
      });
      mo.observe(content, { childList: true, subtree: true });
    }
  }

  function checkProgress() {
    const progress = drawer.querySelector('.alx-mini-cart__progress');
    if (!progress) return;
    const remainingAttr = progress.getAttribute('data-remaining');
    const remaining = remainingAttr ? parseFloat(remainingAttr) : NaN;
    if (!Number.isNaN(remaining) && remaining <= 0) {
      if (!announcedUnlock) {
        announcedUnlock = true;
        if (live) live.textContent = 'Free shipping unlocked!';
        if (!prefersReduced()) {
          progress.classList.add('alx-flash');
          setTimeout(() => progress.classList.remove('alx-flash'), 900);
        }
      }
    }
  }
})();
