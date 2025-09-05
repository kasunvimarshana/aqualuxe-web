export function initWishlist(root = document) {
  root.querySelectorAll('[data-alx-wishlist]')?.forEach(el => {
    el.addEventListener('click', (e) => {
      e.preventDefault();
      const id = parseInt(el.getAttribute('data-product-id'), 10);
      if (!id) return;
      const cookie = document.cookie.split('; ').find(r => r.startsWith('alx_wishlist='));
      const ids = cookie ? cookie.split('=')[1].split(',').filter(Boolean).map(Number) : [];
      if (!ids.includes(id)) ids.push(id);
      document.cookie = `alx_wishlist=${ids.join(',')};path=/;max-age=31536000`;
      el.classList.add('text-red-600');
    });
  });
}
