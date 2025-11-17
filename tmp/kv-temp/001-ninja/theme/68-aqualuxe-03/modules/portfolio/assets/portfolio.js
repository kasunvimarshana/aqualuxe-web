// Portfolio module JS (gallery, lightbox, masonry)
document.addEventListener('DOMContentLoaded', function() {
  // Simple lightbox for images
  document.querySelectorAll('.portfolio-gallery img').forEach(function(img) {
    img.addEventListener('click', function() {
      const src = img.getAttribute('src');
      const overlay = document.createElement('div');
      overlay.className = 'portfolio-lightbox-overlay';
      overlay.innerHTML = `<div class='portfolio-lightbox'><img src='${src}' alt='Portfolio Image' /></div>`;
      overlay.addEventListener('click', function() {
        document.body.removeChild(overlay);
      });
      document.body.appendChild(overlay);
    });
  });
});
