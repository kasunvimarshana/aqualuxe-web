export function initMobileMenu(){
  const toggle = document.getElementById('mobileMenuToggle');
  const panel = document.getElementById('mobileMenu');
  if (!toggle || !panel) return;
  let lastFocus = null;
  const isOpen = () => !panel.hasAttribute('hidden');
  const setOpen = (on) => {
    panel.toggleAttribute('hidden', !on);
    toggle.setAttribute('aria-expanded', on ? 'true' : 'false');
    if (on) { lastFocus = document.activeElement; (panel.querySelector('a,button,[tabindex]') || toggle).focus(); }
    else if (lastFocus && typeof lastFocus.focus === 'function') { lastFocus.focus(); }
  };
  toggle.addEventListener('click', () => setOpen(!isOpen()));
  document.addEventListener('keydown', (e) => {
    if (!isOpen()) return;
    if (e.key === 'Escape') { e.preventDefault(); setOpen(false); }
  });
}
