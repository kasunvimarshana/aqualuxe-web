// Import Alpine.js
import Alpine from 'alpinejs';

// Make Alpine available globally
window.Alpine = Alpine;

// Define Alpine.js data and components
document.addEventListener('DOMContentLoaded', () => {
  // Dark mode functionality
  Alpine.store('darkMode', {
    dark: localStorage.getItem('darkMode') === 'true',
    toggle() {
      this.dark = !this.dark;
      localStorage.setItem('darkMode', this.dark);
      this.updateClasses();
    },
    updateClasses() {
      if (this.dark) {
        document.documentElement.classList.add('dark');
      } else {
        document.documentElement.classList.remove('dark');
      }
    },
    init() {
      this.updateClasses();
    }
  });

  // Mobile menu
  Alpine.data('mobileMenu', () => ({
    open: false,
    toggle() {
      this.open = !this.open;
    },
    close() {
      this.open = false;
    }
  }));

  // Product quick view
  Alpine.data('quickView', () => ({
    open: false,
    productId: null,
    loading: false,
    productData: null,
    
    openQuickView(id) {
      this.productId = id;
      this.loading = true;
      this.open = true;
      
      // Fetch product data via AJAX
      fetch(`/wp-json/wc/v3/products/${id}`)
        .then(response => response.json())
        .then(data => {
          this.productData = data;
          this.loading = false;
        })
        .catch(error => {
          console.error('Error fetching product data:', error);
          this.loading = false;
        });
    },
    
    close() {
      this.open = false;
      this.productId = null;
      this.productData = null;
    }
  }));

  // Mega menu
  Alpine.data('megaMenu', () => ({
    activeMenu: null,
    
    toggleMenu(menu) {
      if (this.activeMenu === menu) {
        this.activeMenu = null;
      } else {
        this.activeMenu = menu;
      }
    },
    
    closeAll() {
      this.activeMenu = null;
    }
  }));

  // Product filters
  Alpine.data('productFilters', () => ({
    filtersOpen: window.innerWidth >= 768,
    activeFilters: {},
    
    toggleFilters() {
      this.filtersOpen = !this.filtersOpen;
    },
    
    applyFilter(category, value) {
      if (!this.activeFilters[category]) {
        this.activeFilters[category] = [];
      }
      
      if (this.activeFilters[category].includes(value)) {
        this.activeFilters[category] = this.activeFilters[category].filter(v => v !== value);
        if (this.activeFilters[category].length === 0) {
          delete this.activeFilters[category];
        }
      } else {
        this.activeFilters[category].push(value);
      }
      
      // Trigger filter event for potential AJAX filtering
      this.triggerFilterEvent();
    },
    
    clearFilters() {
      this.activeFilters = {};
      this.triggerFilterEvent();
    },
    
    triggerFilterEvent() {
      const event = new CustomEvent('product-filter-change', {
        detail: { filters: this.activeFilters }
      });
      window.dispatchEvent(event);
    },
    
    isActive(category, value) {
      return this.activeFilters[category] && this.activeFilters[category].includes(value);
    }
  }));

  // Initialize Alpine
  Alpine.start();
});

// WooCommerce specific functionality
document.addEventListener('DOMContentLoaded', () => {
  // Quantity input buttons
  const quantityInputs = document.querySelectorAll('.quantity input[type="number"]');
  
  quantityInputs.forEach(input => {
    const wrapper = document.createElement('div');
    wrapper.className = 'flex items-center';
    
    const minusBtn = document.createElement('button');
    minusBtn.type = 'button';
    minusBtn.className = 'p-2 bg-light-dark dark:bg-dark-light rounded-l-md';
    minusBtn.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" /></svg>';
    minusBtn.addEventListener('click', () => {
      if (input.value > input.min) {
        input.value = parseInt(input.value) - 1;
        input.dispatchEvent(new Event('change', { bubbles: true }));
      }
    });
    
    const plusBtn = document.createElement('button');
    plusBtn.type = 'button';
    plusBtn.className = 'p-2 bg-light-dark dark:bg-dark-light rounded-r-md';
    plusBtn.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>';
    plusBtn.addEventListener('click', () => {
      if (input.value < input.max || !input.max) {
        input.value = parseInt(input.value) + 1;
        input.dispatchEvent(new Event('change', { bubbles: true }));
      }
    });
    
    input.parentNode.insertBefore(wrapper, input);
    wrapper.appendChild(minusBtn);
    wrapper.appendChild(input);
    wrapper.appendChild(plusBtn);
    
    input.classList.add('text-center', 'border-y', 'border-light-dark', 'dark:border-dark-light', 'h-10', 'focus:ring-0', 'focus:border-primary', 'dark:focus:border-secondary');
    input.style.borderRadius = '0';
  });

  // Product gallery
  const productGallery = document.querySelector('.woocommerce-product-gallery');
  if (productGallery) {
    const mainImage = productGallery.querySelector('.woocommerce-product-gallery__image img');
    const thumbnails = productGallery.querySelectorAll('.woocommerce-product-gallery__thumbnails img');
    
    thumbnails.forEach(thumb => {
      thumb.addEventListener('click', () => {
        mainImage.src = thumb.dataset.large;
        mainImage.srcset = thumb.dataset.srcset || '';
        
        thumbnails.forEach(t => t.classList.remove('ring-2', 'ring-primary', 'dark:ring-secondary'));
        thumb.classList.add('ring-2', 'ring-primary', 'dark:ring-secondary');
      });
    });
  }
});

// Handle smooth scrolling for anchor links
document.addEventListener('click', (e) => {
  const target = e.target.closest('a[href^="#"]');
  if (!target) return;
  
  const id = target.getAttribute('href');
  if (!id || id === '#') return;
  
  const element = document.querySelector(id);
  if (!element) return;
  
  e.preventDefault();
  
  window.scrollTo({
    top: element.offsetTop - 100,
    behavior: 'smooth'
  });
});

// Initialize components when DOM is fully loaded
window.addEventListener('load', () => {
  // Check for system dark mode preference
  if (localStorage.getItem('darkMode') === null && 
      window.matchMedia('(prefers-color-scheme: dark)').matches) {
    Alpine.store('darkMode').dark = true;
    Alpine.store('darkMode').updateClasses();
  }
  
  // Handle system theme changes
  window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', e => {
    if (localStorage.getItem('darkMode') === null) {
      Alpine.store('darkMode').dark = e.matches;
      Alpine.store('darkMode').updateClasses();
    }
  });
});