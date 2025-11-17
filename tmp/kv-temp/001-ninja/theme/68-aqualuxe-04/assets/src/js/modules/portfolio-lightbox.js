// Portfolio Lightbox JS
import GLightbox from 'glightbox';

document.addEventListener('DOMContentLoaded', function () {
  if (document.querySelectorAll('.portfolio-lightbox').length) {
    GLightbox({ selector: '.portfolio-lightbox' });
  }
});
