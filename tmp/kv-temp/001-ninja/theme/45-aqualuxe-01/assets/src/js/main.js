/**
 * AquaLuxe Theme Main JavaScript
 * 
 * This file contains all the main JavaScript functionality for the AquaLuxe theme.
 */

// Import dependencies
import Alpine from 'alpinejs';
import AOS from 'aos';
import 'slick-carousel';
import '@fancyapps/fancybox';

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

// Mobile Menu Toggle
document.addEventListener('DOMContentLoaded', () => {
  const mobileMenuToggle = document.getElementById('mobile-menu-toggle');
  const mobileMenu = document.getElementById('mobile-menu');
  const mobileMenuClose = document.getElementById('mobile-menu-close');
  
  if (mobileMenuToggle && mobileMenu) {
    mobileMenuToggle.addEventListener('click', () => {
      mobileMenu.classList.toggle('hidden');
      document.body.classList.toggle('overflow-hidden');
    });
    
    if (mobileMenuClose) {
      mobileMenuClose.addEventListener('click', () => {
        mobileMenu.classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
      });
    }
  }
});

// Initialize slick carousel for featured products
document.addEventListener('DOMContentLoaded', () => {
  if (jQuery('.featured-products-carousel').length) {
    jQuery('.featured-products-carousel').slick({
      dots: true,
      arrows: true,
      infinite: true,
      speed: 500,
      slidesToShow: 4,
      slidesToScroll: 1,
      autoplay: true,
      autoplaySpeed: 5000,
      responsive: [
        {
          breakpoint: 1280,
          settings: {
            slidesToShow: 3,
          }
        },
        {
          breakpoint: 1024,
          settings: {
            slidesToShow: 2,
          }
        },
        {
          breakpoint: 640,
          settings: {
            slidesToShow: 1,
          }
        }
      ]
    });
  }
});

// Initialize slick carousel for testimonials
document.addEventListener('DOMContentLoaded', () => {
  if (jQuery('.testimonials-carousel').length) {
    jQuery('.testimonials-carousel').slick({
      dots: true,
      arrows: false,
      infinite: true,
      speed: 500,
      slidesToShow: 1,
      slidesToScroll: 1,
      autoplay: true,
      autoplaySpeed: 8000,
      fade: true,
      cssEase: 'linear'
    });
  }
});

// Initialize FancyBox for image galleries
document.addEventListener('DOMContentLoaded', () => {
  jQuery('[data-fancybox]').fancybox({
    buttons: [
      'slideShow',
      'fullScreen',
      'thumbs',
      'close'
    ],
    loop: true,
    protect: true
  });
});

// Smooth scrolling for anchor links
document.addEventListener('DOMContentLoaded', () => {
  document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
      e.preventDefault();
      
      const targetId = this.getAttribute('href');
      if (targetId === '#') return;
      
      const targetElement = document.querySelector(targetId);
      if (targetElement) {
        window.scrollTo({
          top: targetElement.offsetTop - 100,
          behavior: 'smooth'
        });
      }
    });
  });
});

// Form validation
document.addEventListener('DOMContentLoaded', () => {
  const forms = document.querySelectorAll('.validate-form');
  
  forms.forEach(form => {
    form.addEventListener('submit', function(e) {
      let isValid = true;
      const requiredFields = form.querySelectorAll('[required]');
      
      requiredFields.forEach(field => {
        if (!field.value.trim()) {
          isValid = false;
          field.classList.add('border-red-500');
          
          // Add error message if it doesn't exist
          let errorMessage = field.nextElementSibling;
          if (!errorMessage || !errorMessage.classList.contains('error-message')) {
            errorMessage = document.createElement('p');
            errorMessage.classList.add('error-message', 'text-red-500', 'text-sm', 'mt-1');
            errorMessage.textContent = 'This field is required';
            field.parentNode.insertBefore(errorMessage, field.nextSibling);
          }
        } else {
          field.classList.remove('border-red-500');
          
          // Remove error message if it exists
          const errorMessage = field.nextElementSibling;
          if (errorMessage && errorMessage.classList.contains('error-message')) {
            errorMessage.remove();
          }
        }
      });
      
      if (!isValid) {
        e.preventDefault();
      }
    });
  });
});

// Dark mode toggle
document.addEventListener('DOMContentLoaded', () => {
  const darkModeToggle = document.getElementById('dark-mode-toggle');
  
  if (darkModeToggle) {
    // Check for saved theme preference or respect OS preference
    if (localStorage.theme === 'dark' || 
        (!('theme' in localStorage) && 
         window.matchMedia('(prefers-color-scheme: dark)').matches)) {
      document.documentElement.classList.add('dark');
      darkModeToggle.checked = true;
    } else {
      document.documentElement.classList.remove('dark');
      darkModeToggle.checked = false;
    }
    
    // Listen for toggle changes
    darkModeToggle.addEventListener('change', function() {
      if (this.checked) {
        document.documentElement.classList.add('dark');
        localStorage.theme = 'dark';
      } else {
        document.documentElement.classList.remove('dark');
        localStorage.theme = 'light';
      }
    });
  }
});

// AJAX loading for products/posts
document.addEventListener('DOMContentLoaded', () => {
  const loadMoreBtn = document.getElementById('load-more');
  
  if (loadMoreBtn) {
    loadMoreBtn.addEventListener('click', function(e) {
      e.preventDefault();
      
      const button = this;
      const container = document.querySelector(button.dataset.container);
      const page = parseInt(button.dataset.page) || 1;
      const maxPages = parseInt(button.dataset.maxPages) || 1;
      
      if (page >= maxPages) {
        button.style.display = 'none';
        return;
      }
      
      button.disabled = true;
      button.classList.add('opacity-50');
      button.textContent = 'Loading...';
      
      const xhr = new XMLHttpRequest();
      xhr.open('POST', aqualuxe_ajax.ajax_url, true);
      xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
      
      xhr.onload = function() {
        if (xhr.status === 200) {
          const response = JSON.parse(xhr.responseText);
          
          if (response.success && response.data) {
            // Append new content
            container.insertAdjacentHTML('beforeend', response.data);
            
            // Update button state
            button.dataset.page = page + 1;
            button.disabled = false;
            button.classList.remove('opacity-50');
            button.textContent = 'Load More';
            
            // Hide button if we've reached the max pages
            if (page + 1 >= maxPages) {
              button.style.display = 'none';
            }
            
            // Reinitialize AOS for new elements
            AOS.refresh();
          }
        }
      };
      
      xhr.send(
        'action=aqualuxe_load_more' +
        '&nonce=' + aqualuxe_ajax.nonce +
        '&page=' + page +
        '&post_type=' + (button.dataset.postType || 'post')
      );
    });
  }
});