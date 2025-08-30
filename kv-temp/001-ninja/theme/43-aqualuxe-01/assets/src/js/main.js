/**
 * AquaLuxe Theme Main JavaScript
 *
 * @package AquaLuxe
 */

// Import dependencies
import Alpine from 'alpinejs';
import AOS from 'aos';
import GLightbox from 'glightbox';
import Swiper, { Navigation, Pagination, Autoplay } from 'swiper';
import Cookies from 'js-cookie';

// Initialize Alpine.js
window.Alpine = Alpine;
Alpine.start();

// Initialize AOS (Animate on Scroll)
document.addEventListener('DOMContentLoaded', () => {
  AOS.init({
    duration: 800,
    easing: 'ease-in-out',
    once: true,
    mirror: false
  });
});

// Initialize GLightbox for image galleries
const lightbox = GLightbox({
  touchNavigation: true,
  loop: true,
  autoplayVideos: true
});

// Initialize Swiper for sliders
Swiper.use([Navigation, Pagination, Autoplay]);
const initSliders = () => {
  // Hero slider
  const heroSlider = document.querySelector('.hero-slider');
  if (heroSlider) {
    new Swiper(heroSlider, {
      slidesPerView: 1,
      spaceBetween: 0,
      loop: true,
      autoplay: {
        delay: 5000,
        disableOnInteraction: false,
      },
      pagination: {
        el: '.swiper-pagination',
        clickable: true,
      },
      navigation: {
        nextEl: '.swiper-button-next',
        prevEl: '.swiper-button-prev',
      },
    });
  }

  // Testimonials slider
  const testimonialSlider = document.querySelector('.testimonial-slider');
  if (testimonialSlider) {
    new Swiper(testimonialSlider, {
      slidesPerView: 1,
      spaceBetween: 30,
      loop: true,
      autoplay: {
        delay: 4000,
        disableOnInteraction: false,
      },
      pagination: {
        el: '.swiper-pagination',
        clickable: true,
      },
      breakpoints: {
        640: {
          slidesPerView: 1,
        },
        768: {
          slidesPerView: 2,
        },
        1024: {
          slidesPerView: 3,
        },
      },
    });
  }

  // Partners slider
  const partnersSlider = document.querySelector('.partners-slider');
  if (partnersSlider) {
    new Swiper(partnersSlider, {
      slidesPerView: 2,
      spaceBetween: 30,
      loop: true,
      autoplay: {
        delay: 3000,
        disableOnInteraction: false,
      },
      breakpoints: {
        640: {
          slidesPerView: 3,
        },
        768: {
          slidesPerView: 4,
        },
        1024: {
          slidesPerView: 5,
        },
      },
    });
  }
};

// Initialize sliders
document.addEventListener('DOMContentLoaded', initSliders);

/**
 * Lazy Loading Implementation
 */
document.addEventListener('DOMContentLoaded', () => {
  // Lazy load images
  const lazyImages = document.querySelectorAll('img.lazy');
  
  if ('IntersectionObserver' in window) {
    const imageObserver = new IntersectionObserver((entries, observer) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          const img = entry.target;
          img.src = img.dataset.src;
          
          if (img.dataset.srcset) {
            img.srcset = img.dataset.srcset;
          }
          
          img.classList.remove('lazy');
          imageObserver.unobserve(img);
        }
      });
    });

    lazyImages.forEach(img => {
      imageObserver.observe(img);
    });
  } else {
    // Fallback for browsers that don't support IntersectionObserver
    let lazyLoadThrottleTimeout;
    
    function lazyLoad() {
      if (lazyLoadThrottleTimeout) {
        clearTimeout(lazyLoadThrottleTimeout);
      }

      lazyLoadThrottleTimeout = setTimeout(() => {
        const scrollTop = window.pageYOffset;
        
        lazyImages.forEach(img => {
          if (img.offsetTop < window.innerHeight + scrollTop) {
            img.src = img.dataset.src;
            
            if (img.dataset.srcset) {
              img.srcset = img.dataset.srcset;
            }
            
            img.classList.remove('lazy');
          }
        });
        
        if (lazyImages.length === 0) {
          document.removeEventListener('scroll', lazyLoad);
          window.removeEventListener('resize', lazyLoad);
          window.removeEventListener('orientationChange', lazyLoad);
        }
      }, 20);
    }

    document.addEventListener('scroll', lazyLoad);
    window.addEventListener('resize', lazyLoad);
    window.addEventListener('orientationChange', lazyLoad);
  }

  // Lazy load background images
  const lazyBackgrounds = document.querySelectorAll('.lazy-background');
  
  if ('IntersectionObserver' in window) {
    const backgroundObserver = new IntersectionObserver((entries, observer) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          const element = entry.target;
          element.style.backgroundImage = `url(${element.dataset.background})`;
          element.classList.remove('lazy-background');
          backgroundObserver.unobserve(element);
        }
      });
    });

    lazyBackgrounds.forEach(bg => {
      backgroundObserver.observe(bg);
    });
  }
});

/**
 * Dark Mode Toggle
 */
const darkModeToggle = document.getElementById('dark-mode-toggle');
if (darkModeToggle) {
  darkModeToggle.addEventListener('click', () => {
    document.documentElement.classList.toggle('dark');
    
    // Save preference to cookie
    const isDarkMode = document.documentElement.classList.contains('dark');
    Cookies.set('aqualuxe_dark_mode', isDarkMode ? '1' : '0', { expires: 30 });
    
    // Update toggle aria-pressed state
    darkModeToggle.setAttribute('aria-pressed', isDarkMode ? 'true' : 'false');
  });
}

/**
 * Mobile Menu Toggle
 */
const mobileMenuToggle = document.getElementById('mobile-menu-toggle');
const mobileMenu = document.getElementById('mobile-menu');

if (mobileMenuToggle && mobileMenu) {
  mobileMenuToggle.addEventListener('click', () => {
    const expanded = mobileMenuToggle.getAttribute('aria-expanded') === 'true';
    mobileMenuToggle.setAttribute('aria-expanded', !expanded);
    mobileMenu.classList.toggle('hidden');
  });
}

/**
 * Search Modal Toggle
 */
const searchToggle = document.getElementById('search-toggle');
const searchModal = document.getElementById('search-modal');
const searchModalClose = document.getElementById('search-modal-close');
const searchInput = document.getElementById('search-input');

if (searchToggle && searchModal) {
  searchToggle.addEventListener('click', (e) => {
    e.preventDefault();
    searchModal.classList.remove('hidden');
    document.body.classList.add('overflow-hidden');
    
    // Focus the search input
    if (searchInput) {
      setTimeout(() => {
        searchInput.focus();
      }, 100);
    }
  });
  
  if (searchModalClose) {
    searchModalClose.addEventListener('click', () => {
      searchModal.classList.add('hidden');
      document.body.classList.remove('overflow-hidden');
    });
  }
  
  // Close on escape key
  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape' && !searchModal.classList.contains('hidden')) {
      searchModal.classList.add('hidden');
      document.body.classList.remove('overflow-hidden');
    }
  });
  
  // Close on click outside
  searchModal.addEventListener('click', (e) => {
    if (e.target === searchModal) {
      searchModal.classList.add('hidden');
      document.body.classList.remove('overflow-hidden');
    }
  });
}

/**
 * Back to Top Button
 */
const backToTopButton = document.getElementById('back-to-top');

if (backToTopButton) {
  window.addEventListener('scroll', () => {
    if (window.pageYOffset > 300) {
      backToTopButton.classList.remove('hidden');
    } else {
      backToTopButton.classList.add('hidden');
    }
  });
  
  backToTopButton.addEventListener('click', (e) => {
    e.preventDefault();
    window.scrollTo({ top: 0, behavior: 'smooth' });
  });
}

/**
 * Newsletter Form Submission
 */
const newsletterForm = document.getElementById('newsletter-form');

if (newsletterForm) {
  newsletterForm.addEventListener('submit', async (e) => {
    e.preventDefault();
    
    const email = newsletterForm.querySelector('input[type="email"]').value;
    const submitButton = newsletterForm.querySelector('button[type="submit"]');
    const responseMessage = document.getElementById('newsletter-response');
    
    if (!email) {
      if (responseMessage) {
        responseMessage.textContent = 'Please enter your email address.';
        responseMessage.classList.add('text-red-500');
        responseMessage.classList.remove('text-green-500');
      }
      return;
    }
    
    // Disable button and show loading state
    if (submitButton) {
      submitButton.disabled = true;
      submitButton.innerHTML = 'Subscribing...';
    }
    
    try {
      // This would typically be an AJAX request to your server
      // For now, we'll just simulate a successful subscription
      await new Promise(resolve => setTimeout(resolve, 1000));
      
      if (responseMessage) {
        responseMessage.textContent = 'Thank you for subscribing!';
        responseMessage.classList.add('text-green-500');
        responseMessage.classList.remove('text-red-500');
      }
      
      // Reset form
      newsletterForm.reset();
    } catch (error) {
      if (responseMessage) {
        responseMessage.textContent = 'An error occurred. Please try again.';
        responseMessage.classList.add('text-red-500');
        responseMessage.classList.remove('text-green-500');
      }
    } finally {
      // Re-enable button
      if (submitButton) {
        submitButton.disabled = false;
        submitButton.innerHTML = 'Subscribe';
      }
    }
  });
}