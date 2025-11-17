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

// Quick View (progressive enhancement)
const qvModal = document.getElementById('qv-modal');
const qvBackdrop = document.getElementById('qv-backdrop');
const qvContent = document.getElementById('qv-content');
const qvClose = document.getElementById('qv-close');
let qvLastFocus = null;

function qvOpen(html) {
  if (!qvModal || !qvBackdrop || !qvContent) return;
  qvContent.innerHTML = html;
  qvModal.removeAttribute('hidden');
  qvBackdrop.removeAttribute('hidden');
  qvModal.classList.add('open');
  qvBackdrop.classList.add('open');
  qvLastFocus = document.activeElement;
  const focusable = qvModal.querySelector('button, [href], input, select, textarea, [tabindex]:not([tabindex="-1"])');
  (focusable || qvClose || qvModal).focus();
}
function qvHide() {
  if (!qvModal || !qvBackdrop) return;
  qvModal.classList.remove('open');
  qvBackdrop.classList.remove('open');
  qvModal.setAttribute('hidden', '');
  qvBackdrop.setAttribute('hidden', '');
  if (qvLastFocus && typeof qvLastFocus.focus === 'function') {
    qvLastFocus.focus();
  }
}
if (qvClose) qvClose.addEventListener('click', qvHide);
document.addEventListener('keydown', (e) => {
  if (e.key === 'Escape' && qvModal && !qvModal.hasAttribute('hidden')) {
    qvHide();
  }
});
document.addEventListener('click', (e) => {
  const t = e.target.closest('[data-qv-id]');
  if (!t) return;
  e.preventDefault();
  const id = t.getAttribute('data-qv-id');
  const url = (window.AQUALUXE?.restUrl || '').replace(/\/$/, '') + '/quickview/' + encodeURIComponent(id);
  fetch(url, { headers: { 'X-WP-Nonce': window.AQUALUXE?.nonce || '' }})
    .then(r => r.json())
    .then(({html}) => qvOpen(html || ''))
    .catch(() => qvOpen('<div class="p-6">Error</div>'));
});
