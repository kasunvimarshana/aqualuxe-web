// AquaLuxe WooCommerce Product Quick View
(function() {
  document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.product-quick-view-btn').forEach(function(btn) {
      btn.addEventListener('click', function(e) {
        e.preventDefault();
        const productId = btn.getAttribute('data-product-id');
        fetch(aqualuxe_quick_view.ajax_url + '?action=aqualuxe_quick_view&product_id=' + productId)
          .then(res => res.text())
          .then(html => {
            const modal = document.createElement('div');
            modal.className = 'aqualuxe-quick-view-overlay';
            modal.innerHTML = html;
            document.body.appendChild(modal);
            modal.querySelector('.quick-view-close').addEventListener('click', function() {
              document.body.removeChild(modal);
            });
          });
      });
    });
  });
})();
