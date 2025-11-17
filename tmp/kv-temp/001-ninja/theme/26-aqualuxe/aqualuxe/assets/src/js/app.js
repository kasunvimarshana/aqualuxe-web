/**
 * Main JavaScript file for AquaLuxe theme
 */

// Import dependencies
import './components/navigation';
import './components/dark-mode';
import './components/animations';
import './components/utils';

// Import WooCommerce specific scripts
if (typeof woocommerce_params !== 'undefined') {
  import('./components/woocommerce').then(module => {
    // WooCommerce scripts loaded
  });
  
  // Load quick view functionality if enabled
  if (typeof aqualuxeQuickView !== 'undefined') {
    import('./components/quick-view').then(module => {
      // Quick view scripts loaded
    });
  }
}

// Document ready
document.addEventListener('DOMContentLoaded', function() {
  console.log('AquaLuxe theme initialized');
  
  // Initialize page loader
  const pageLoader = document.querySelector('.page-loader');
  if (pageLoader) {
    window.addEventListener('load', function() {
      pageLoader.classList.add('fade-out');
      setTimeout(() => {
        pageLoader.remove();
      }, 500);
    });
  }
  
  // Initialize back to top button
  const backToTopButton = document.getElementById('back-to-top');
  if (backToTopButton) {
    window.addEventListener('scroll', function() {
      if (window.scrollY > 300) {
        backToTopButton.classList.add('show');
      } else {
        backToTopButton.classList.remove('show');
      }
    });
    
    backToTopButton.addEventListener('click', function(e) {
      e.preventDefault();
      window.scrollTo({
        top: 0,
        behavior: 'smooth'
      });
    });
  }
  
  // Initialize tooltips
  const tooltipElements = document.querySelectorAll('[data-tooltip]');
  tooltipElements.forEach(element => {
    element.addEventListener('mouseenter', function() {
      const tooltipText = this.getAttribute('data-tooltip');
      const tooltip = document.createElement('div');
      tooltip.className = 'tooltip';
      tooltip.textContent = tooltipText;
      document.body.appendChild(tooltip);
      
      const elementRect = this.getBoundingClientRect();
      const tooltipRect = tooltip.getBoundingClientRect();
      
      tooltip.style.top = (elementRect.top - tooltipRect.height - 10) + 'px';
      tooltip.style.left = (elementRect.left + (elementRect.width / 2) - (tooltipRect.width / 2)) + 'px';
      
      setTimeout(() => {
        tooltip.classList.add('show');
      }, 10);
      
      this.addEventListener('mouseleave', function() {
        tooltip.classList.remove('show');
        setTimeout(() => {
          tooltip.remove();
        }, 300);
      }, { once: true });
    });
  });
  
  // Handle responsive tables
  const tables = document.querySelectorAll('table');
  tables.forEach(table => {
    if (!table.parentElement.classList.contains('table-responsive')) {
      const wrapper = document.createElement('div');
      wrapper.className = 'table-responsive';
      table.parentNode.insertBefore(wrapper, table);
      wrapper.appendChild(table);
    }
  });
  
  // Handle responsive embeds
  const iframes = document.querySelectorAll('iframe[src*="youtube.com"], iframe[src*="vimeo.com"]');
  iframes.forEach(iframe => {
    if (!iframe.parentElement.classList.contains('responsive-embed')) {
      const wrapper = document.createElement('div');
      wrapper.className = 'responsive-embed';
      iframe.parentNode.insertBefore(wrapper, iframe);
      wrapper.appendChild(iframe);
    }
  });
  
  // Initialize custom select dropdowns
  const customSelects = document.querySelectorAll('.custom-select');
  customSelects.forEach(select => {
    const wrapper = document.createElement('div');
    wrapper.className = 'custom-select-wrapper';
    
    const trigger = document.createElement('div');
    trigger.className = 'custom-select-trigger';
    trigger.textContent = select.options[select.selectedIndex].textContent;
    
    const options = document.createElement('div');
    options.className = 'custom-select-options';
    
    // Create custom options
    Array.from(select.options).forEach((option, index) => {
      const customOption = document.createElement('div');
      customOption.className = 'custom-select-option';
      customOption.textContent = option.textContent;
      customOption.dataset.value = option.value;
      
      if (index === select.selectedIndex) {
        customOption.classList.add('selected');
      }
      
      customOption.addEventListener('click', function() {
        select.value = this.dataset.value;
        trigger.textContent = this.textContent;
        
        // Update selected class
        options.querySelectorAll('.custom-select-option').forEach(opt => {
          opt.classList.remove('selected');
        });
        this.classList.add('selected');
        
        // Trigger change event
        const event = new Event('change', { bubbles: true });
        select.dispatchEvent(event);
        
        // Close dropdown
        wrapper.classList.remove('open');
      });
      
      options.appendChild(customOption);
    });
    
    // Toggle dropdown on click
    trigger.addEventListener('click', function(e) {
      e.stopPropagation();
      wrapper.classList.toggle('open');
    });
    
    // Close dropdown when clicking outside
    document.addEventListener('click', function() {
      wrapper.classList.remove('open');
    });
    
    // Add elements to DOM
    wrapper.appendChild(trigger);
    wrapper.appendChild(options);
    select.parentNode.insertBefore(wrapper, select);
    select.style.display = 'none';
  });
});