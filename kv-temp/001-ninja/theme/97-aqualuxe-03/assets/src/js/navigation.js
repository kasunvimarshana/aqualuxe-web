/**
 * AquaLuxe Theme - Navigation
 * Accessible, mobile-first navigation handling with focus trapping
 * and ARIA updates. No external deps.
 */
(function () {
  const qs = (s, ctx = document) => ctx.querySelector(s);
  const qsa = (s, ctx = document) => Array.from(ctx.querySelectorAll(s));

  function enhanceNav() {
    const toggle = qs('[data-nav-toggle]');
    const menu = qs('[data-nav-menu]');
    if (!toggle || !menu) return;

    const focusable = () =>
      qsa('a, button, input, [tabindex]:not([tabindex="-1"])', menu).filter(
        el => !el.hasAttribute('disabled')
      );
    let open = false;

    function setState(state) {
      open = state;
      menu.classList.toggle('is-open', open);
      toggle.setAttribute('aria-expanded', String(open));
      menu.setAttribute('aria-hidden', String(!open));
      if (open) {
        const first = focusable()[0];
        if (first) first.focus();
        document.addEventListener('keydown', onKeyDown);
      } else {
        document.removeEventListener('keydown', onKeyDown);
        toggle.focus();
      }
    }

    function onKeyDown(e) {
      if (e.key === 'Escape') {
        setState(false);
      } else if (e.key === 'Tab' && open) {
        const items = focusable();
        if (items.length === 0) return;
        const first = items[0];
        const last = items[items.length - 1];
        if (e.shiftKey && document.activeElement === first) {
          e.preventDefault();
          last.focus();
        } else if (!e.shiftKey && document.activeElement === last) {
          e.preventDefault();
          first.focus();
        }
      }
    }

    toggle.addEventListener('click', () => setState(!open));

    // Close when clicking outside
    document.addEventListener('click', e => {
      if (!open) return;
      if (!menu.contains(e.target) && !toggle.contains(e.target)) {
        setState(false);
      }
    });
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', enhanceNav);
  } else {
    enhanceNav();
  }
})();
