import '../css/app.css';

// Dark mode toggle with persistent preference
const toggle = document.getElementById('darkModeToggle');
if (toggle) {
  const setPressed = (on) => toggle.setAttribute('aria-pressed', on ? 'true' : 'false');
  const apply = (mode) => {
    const root = document.documentElement;
    if (mode === 'dark') {
      root.classList.add('dark');
      setPressed(true);
    } else {
      root.classList.remove('dark');
      setPressed(false);
    }
  };
  try {
    const pref = localStorage.getItem('aqualuxe:theme');
    apply(pref);
  } catch (e) {}
  toggle.addEventListener('click', () => {
    const isDark = document.documentElement.classList.toggle('dark');
    try { localStorage.setItem('aqualuxe:theme', isDark ? 'dark' : 'light'); } catch (e) {}
    setPressed(isDark);
  });
}

// Progressive enhancement: lazy loading images
document.querySelectorAll('img[loading="lazy"]').forEach(img => {
  img.decoding = 'async';
});

// Quick View (progressive enhancement + a11y)
const qvModal = document.getElementById('qv-modal');
const qvBackdrop = document.getElementById('qv-backdrop');
const qvContent = document.getElementById('qv-content');
const qvClose = document.getElementById('qv-close');
const a11yLive = document.getElementById('a11y-live');
let qvLastFocus = null;

const isOpen = () => qvModal && !qvModal.hasAttribute('hidden');
const setLive = (msg) => { if (a11yLive) a11yLive.textContent = msg; };
const getFocusable = (root) => Array.from(root.querySelectorAll(
  'a[href], area[href], input:not([disabled]):not([type="hidden"]), select:not([disabled]), textarea:not([disabled]), button:not([disabled]), iframe, [tabindex]:not([tabindex="-1"])'
)).filter(el => el.offsetParent !== null || el === document.activeElement);

function qvOpen(html) {
  if (!qvModal || !qvBackdrop || !qvContent) return;
  qvContent.innerHTML = html;
  qvContent.setAttribute('aria-busy', 'false');
  qvModal.removeAttribute('hidden');
  qvBackdrop.removeAttribute('hidden');
  qvModal.classList.add('open');
  qvBackdrop.classList.add('open');
  qvLastFocus = document.activeElement;
  // Focus the first focusable element inside the modal
  const focusables = getFocusable(qvModal);
  const first = focusables[0] || qvClose || qvModal;
  first.focus();
  setLive('Quick view opened');
}
function qvHide() {
  if (!qvModal || !qvBackdrop) return;
  qvModal.classList.remove('open');
  qvBackdrop.classList.remove('open');
  qvModal.setAttribute('hidden', '');
  qvBackdrop.setAttribute('hidden', '');
  setLive('Quick view closed');
  if (qvLastFocus && typeof qvLastFocus.focus === 'function') {
    qvLastFocus.focus();
  }
}
if (qvClose) qvClose.addEventListener('click', qvHide);
if (qvBackdrop) qvBackdrop.addEventListener('click', qvHide);
document.addEventListener('keydown', (e) => {
  if (!isOpen()) return;
  if (e.key === 'Escape') {
    e.stopPropagation();
    qvHide();
    return;
  }
  if (e.key === 'Tab') {
    const focusables = getFocusable(qvModal);
    if (focusables.length === 0) {
      e.preventDefault();
      (qvClose || qvModal).focus();
      return;
    }
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
document.addEventListener('click', (e) => {
  const t = e.target.closest('[data-qv-id]');
  if (!t) return;
  e.preventDefault();
  const id = t.getAttribute('data-qv-id');
  const url = (window.AQUALUXE?.restUrl || '').replace(/\/$/, '') + '/quickview/' + encodeURIComponent(id);
  // Announce loading state
  if (qvContent) qvContent.setAttribute('aria-busy', 'true');
  setLive('Loading product details…');
  fetch(url, { headers: { 'X-WP-Nonce': window.AQUALUXE?.nonce || '' }})
    .then(r => r.json())
    .then(({html}) => qvOpen(html || ''))
    .catch(() => {
      setLive('Failed to load product details');
      qvOpen('<div class="p-6">Error</div>');
    });
});
