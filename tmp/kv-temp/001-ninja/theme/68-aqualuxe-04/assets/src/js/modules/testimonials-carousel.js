// Testimonials Carousel using Swiper.js
import Swiper from 'swiper';
import 'swiper/css';

document.addEventListener('DOMContentLoaded', function () {
  if (document.querySelector('.testimonials-swiper')) {
    new Swiper('.testimonials-swiper', {
      loop: true,
      slidesPerView: 1,
      autoplay: { delay: 5000 },
      pagination: { el: '.swiper-pagination', clickable: true },
      navigation: { nextEl: '.swiper-button-next', prevEl: '.swiper-button-prev' },
    });
  }
});
