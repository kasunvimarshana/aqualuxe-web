/**
 * AquaLuxe Theme Main JavaScript
 *
 * Contains the main JavaScript functionality for the theme.
 */

// Import Alpine.js
import Alpine from 'alpinejs';
import focus from '@alpinejs/focus';

// Initialize Alpine.js
Alpine.plugin(focus);
window.Alpine = Alpine;
Alpine.start();

document.addEventListener('DOMContentLoaded', function() {
  // Initialize components
  initializeComponents();
  
  // Handle scroll effects
  handleScrollEffects();
  
  // Initialize sliders if any
  initializeSliders();
  
  // Initialize modals
  initializeModals();
  
  // Initialize tooltips
  initializeTooltips();
  
  // Initialize tabs
  initializeTabs();
  
  // Initialize accordions
  initializeAccordions();
});

/**
 * Initialize various components
 */
function initializeComponents() {
  // Dropdown menus
  const dropdownToggles = document.querySelectorAll('.dropdown-toggle');
  
  dropdownToggles.forEach(toggle => {
    toggle.addEventListener('click', function(e) {
      e.preventDefault();
      e.stopPropagation();
      
      const dropdown = this.nextElementSibling;
      const isExpanded = this.getAttribute('aria-expanded') === 'true';
      
      // Close all other dropdowns
      dropdownToggles.forEach(otherToggle => {
        if (otherToggle !== this) {
          otherToggle.setAttribute('aria-expanded', 'false');
          otherToggle.nextElementSibling.classList.add('hidden');
        }
      });
      
      // Toggle current dropdown
      this.setAttribute('aria-expanded', !isExpanded);
      dropdown.classList.toggle('hidden');
    });
  });
  
  // Close dropdowns when clicking outside
  document.addEventListener('click', function() {
    dropdownToggles.forEach(toggle => {
      toggle.setAttribute('aria-expanded', 'false');
      toggle.nextElementSibling.classList.add('hidden');
    });
  });
}

/**
 * Handle scroll effects
 */
function handleScrollEffects() {
  const header = document.querySelector('.site-header');
  let lastScrollTop = 0;
  
  window.addEventListener('scroll', function() {
    const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
    
    // Add shadow to header on scroll
    if (scrollTop > 10) {
      header.classList.add('shadow');
    } else {
      header.classList.remove('shadow');
    }
    
    // Reveal/hide header on scroll up/down
    if (scrollTop > lastScrollTop && scrollTop > 100) {
      // Scrolling down
      header.classList.add('-translate-y-full');
    } else {
      // Scrolling up
      header.classList.remove('-translate-y-full');
    }
    
    lastScrollTop = scrollTop;
    
    // Animate elements on scroll
    animateOnScroll();
  });
}

/**
 * Animate elements when they come into view
 */
function animateOnScroll() {
  const animatedElements = document.querySelectorAll('.animate-on-scroll:not(.animated)');
  
  animatedElements.forEach(element => {
    const elementTop = element.getBoundingClientRect().top;
    const elementBottom = element.getBoundingClientRect().bottom;
    const isVisible = (elementTop < window.innerHeight) && (elementBottom > 0);
    
    if (isVisible) {
      element.classList.add('animated');
    }
  });
}

/**
 * Initialize sliders
 */
function initializeSliders() {
  // Check if there are any sliders on the page
  const sliders = document.querySelectorAll('.aqualuxe-slider');
  
  if (sliders.length === 0) {
    return;
  }
  
  // Simple slider functionality
  sliders.forEach(slider => {
    const slides = slider.querySelectorAll('.aqualuxe-slide');
    const prevButton = slider.querySelector('.aqualuxe-slider-prev');
    const nextButton = slider.querySelector('.aqualuxe-slider-next');
    const dots = slider.querySelectorAll('.aqualuxe-slider-dot');
    
    let currentSlide = 0;
    
    // Show initial slide
    showSlide(currentSlide);
    
    // Previous button
    if (prevButton) {
      prevButton.addEventListener('click', () => {
        currentSlide = (currentSlide - 1 + slides.length) % slides.length;
        showSlide(currentSlide);
      });
    }
    
    // Next button
    if (nextButton) {
      nextButton.addEventListener('click', () => {
        currentSlide = (currentSlide + 1) % slides.length;
        showSlide(currentSlide);
      });
    }
    
    // Dots
    if (dots.length > 0) {
      dots.forEach((dot, index) => {
        dot.addEventListener('click', () => {
          currentSlide = index;
          showSlide(currentSlide);
        });
      });
    }
    
    // Auto-advance if data-auto-slide is set
    if (slider.dataset.autoSlide) {
      const interval = parseInt(slider.dataset.autoSlide, 10) || 5000;
      
      setInterval(() => {
        currentSlide = (currentSlide + 1) % slides.length;
        showSlide(currentSlide);
      }, interval);
    }
    
    // Show slide function
    function showSlide(index) {
      slides.forEach((slide, i) => {
        slide.classList.toggle('hidden', i !== index);
        slide.setAttribute('aria-hidden', i !== index);
      });
      
      if (dots.length > 0) {
        dots.forEach((dot, i) => {
          dot.classList.toggle('bg-primary-500', i === index);
          dot.classList.toggle('bg-gray-300', i !== index);
        });
      }
    }
  });
}

/**
 * Initialize modals
 */
function initializeModals() {
  const modalTriggers = document.querySelectorAll('[data-modal-target]');
  const modalCloseButtons = document.querySelectorAll('[data-modal-close]');
  
  modalTriggers.forEach(trigger => {
    trigger.addEventListener('click', (e) => {
      e.preventDefault();
      const modalId = trigger.getAttribute('data-modal-target');
      const modal = document.getElementById(modalId);
      
      if (modal) {
        modal.classList.remove('hidden');
        document.body.classList.add('overflow-hidden');
        
        // Focus first focusable element
        const focusableElements = modal.querySelectorAll('button, [href], input, select, textarea, [tabindex]:not([tabindex="-1"])');
        if (focusableElements.length > 0) {
          focusableElements[0].focus();
        }
      }
    });
  });
  
  modalCloseButtons.forEach(button => {
    button.addEventListener('click', () => {
      const modal = button.closest('.modal');
      if (modal) {
        modal.classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
      }
    });
  });
  
  // Close modal when clicking outside content
  document.addEventListener('click', (e) => {
    if (e.target.classList.contains('modal')) {
      e.target.classList.add('hidden');
      document.body.classList.remove('overflow-hidden');
    }
  });
  
  // Close modal with Escape key
  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') {
      const openModal = document.querySelector('.modal:not(.hidden)');
      if (openModal) {
        openModal.classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
      }
    }
  });
}

/**
 * Initialize tooltips
 */
function initializeTooltips() {
  const tooltipTriggers = document.querySelectorAll('[data-tooltip]');
  
  tooltipTriggers.forEach(trigger => {
    const tooltipText = trigger.getAttribute('data-tooltip');
    const tooltipPosition = trigger.getAttribute('data-tooltip-position') || 'top';
    
    // Create tooltip element
    const tooltip = document.createElement('div');
    tooltip.className = 'absolute hidden z-50 px-2 py-1 text-xs text-white bg-gray-800 rounded pointer-events-none whitespace-nowrap';
    tooltip.textContent = tooltipText;
    
    // Add tooltip to the DOM
    document.body.appendChild(tooltip);
    
    // Show tooltip on hover/focus
    trigger.addEventListener('mouseenter', showTooltip);
    trigger.addEventListener('focus', showTooltip);
    
    // Hide tooltip
    trigger.addEventListener('mouseleave', hideTooltip);
    trigger.addEventListener('blur', hideTooltip);
    
    function showTooltip() {
      const triggerRect = trigger.getBoundingClientRect();
      
      // Position the tooltip
      switch (tooltipPosition) {
        case 'top':
          tooltip.style.bottom = `${window.innerHeight - triggerRect.top + 5}px`;
          tooltip.style.left = `${triggerRect.left + triggerRect.width / 2}px`;
          tooltip.style.transform = 'translateX(-50%)';
          break;
        case 'bottom':
          tooltip.style.top = `${triggerRect.bottom + 5}px`;
          tooltip.style.left = `${triggerRect.left + triggerRect.width / 2}px`;
          tooltip.style.transform = 'translateX(-50%)';
          break;
        case 'left':
          tooltip.style.top = `${triggerRect.top + triggerRect.height / 2}px`;
          tooltip.style.right = `${window.innerWidth - triggerRect.left + 5}px`;
          tooltip.style.transform = 'translateY(-50%)';
          break;
        case 'right':
          tooltip.style.top = `${triggerRect.top + triggerRect.height / 2}px`;
          tooltip.style.left = `${triggerRect.right + 5}px`;
          tooltip.style.transform = 'translateY(-50%)';
          break;
      }
      
      tooltip.classList.remove('hidden');
    }
    
    function hideTooltip() {
      tooltip.classList.add('hidden');
    }
  });
}

/**
 * Initialize tabs
 */
function initializeTabs() {
  const tabGroups = document.querySelectorAll('.tab-group');
  
  tabGroups.forEach(group => {
    const tabs = group.querySelectorAll('[data-tab]');
    const tabContents = group.querySelectorAll('[data-tab-content]');
    
    tabs.forEach(tab => {
      tab.addEventListener('click', () => {
        const tabId = tab.getAttribute('data-tab');
        
        // Deactivate all tabs
        tabs.forEach(t => {
          t.classList.remove('active', 'border-primary-500', 'text-primary-500');
          t.classList.add('border-transparent', 'text-gray-500');
          t.setAttribute('aria-selected', 'false');
        });
        
        // Activate current tab
        tab.classList.add('active', 'border-primary-500', 'text-primary-500');
        tab.classList.remove('border-transparent', 'text-gray-500');
        tab.setAttribute('aria-selected', 'true');
        
        // Hide all tab contents
        tabContents.forEach(content => {
          content.classList.add('hidden');
        });
        
        // Show current tab content
        const currentContent = group.querySelector(`[data-tab-content="${tabId}"]`);
        if (currentContent) {
          currentContent.classList.remove('hidden');
        }
      });
    });
    
    // Activate first tab by default
    if (tabs.length > 0) {
      tabs[0].click();
    }
  });
}

/**
 * Initialize accordions
 */
function initializeAccordions() {
  const accordionItems = document.querySelectorAll('.accordion-item');
  
  accordionItems.forEach(item => {
    const header = item.querySelector('.accordion-header');
    const content = item.querySelector('.accordion-content');
    
    header.addEventListener('click', () => {
      const isExpanded = header.getAttribute('aria-expanded') === 'true';
      
      // Toggle current item
      header.setAttribute('aria-expanded', !isExpanded);
      content.classList.toggle('hidden');
      
      // If accordion is set to only allow one open item at a time
      if (item.closest('.accordion').dataset.singleOpen) {
        const siblings = [...item.parentElement.children].filter(child => child !== item);
        
        siblings.forEach(sibling => {
          const siblingHeader = sibling.querySelector('.accordion-header');
          const siblingContent = sibling.querySelector('.accordion-content');
          
          siblingHeader.setAttribute('aria-expanded', 'false');
          siblingContent.classList.add('hidden');
        });
      }
    });
  });
}