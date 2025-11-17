// Dark mode toggle logic
import '../css/darkmode.scss';
document.addEventListener('DOMContentLoaded', function() {
  const btn = document.getElementById('dark-mode-toggle');
  if (!btn) return;
  btn.addEventListener('click', function() {
    document.documentElement.classList.toggle('dark');
    localStorage.setItem('aqualuxe-dark', document.documentElement.classList.contains('dark'));
    btn.setAttribute('aria-pressed', document.documentElement.classList.contains('dark'));
  });
  // Persist preference
  if (localStorage.getItem('aqualuxe-dark') === 'true') {
    document.documentElement.classList.add('dark');
    btn.setAttribute('aria-pressed', 'true');
  }
});
