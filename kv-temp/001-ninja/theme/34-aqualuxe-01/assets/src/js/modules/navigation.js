/**
 * Navigation Module
 * 
 * Handles the navigation functionality for the theme.
 */

const Navigation = {
  /**
   * Initialize the navigation functionality
   */
  init() {
    this.cacheDOM();
    this.bindEvents();
    this.setupMobileNav();
    this.setupDropdowns();
  },

  /**
   * Cache DOM elements
   */
  cacheDOM() {
    this.header = document.querySelector('.site-header');
    this.mobileToggle = document.querySelector('.site-header__mobile-toggle');
    this.mobileNav = document.querySelector('.mobile-nav');
    this.mobileNavClose = document.querySelector('.mobile-nav__close');
    this.dropdownMenus = document.querySelectorAll('.menu-item-has-children');
    this.body = document.body;
  },

  /**
   * Bind events
   */
  bindEvents() {
    // Sticky header on scroll
    if (this.header) {
      document.addEventListener('aqualuxe:scroll', event => {
        this.handleStickyHeader(event.detail.position);
      });
    }

    // Mobile navigation toggle
    if (this.mobileToggle && this.mobileNav) {
      this.mobileToggle.addEventListener('click', () => this.toggleMobileNav());
    }

    // Mobile navigation close button
    if (this.mobileNavClose) {
      this.mobileNavClose.addEventListener('click', () => this.closeMobileNav());
    }

    // Close mobile nav when clicking outside
    document.addEventListener('click', event => {
      if (this.mobileNav && this.mobileNav.classList.contains('active')) {
        if (!this.mobileNav.contains(event.target) && event.target !== this.mobileToggle) {
          this.closeMobileNav();
        }
      }
    });

    // Close mobile nav on escape key
    document.addEventListener('keydown', event => {
      if (event.key === 'Escape' && this.mobileNav && this.mobileNav.classList.contains('active')) {
        this.closeMobileNav();
      }
    });

    // Handle window resize
    document.addEventListener('aqualuxe:resize', () => {
      // Close mobile nav on larger screens
      if (window.innerWidth >= 768 && this.mobileNav && this.mobileNav.classList.contains('active')) {
        this.closeMobileNav();
      }
    });
  },

  /**
   * Handle sticky header on scroll
   * 
   * @param {number} scrollPosition - Current scroll position
   */
  handleStickyHeader(scrollPosition) {
    if (scrollPosition > 100) {
      this.header.classList.add('is-sticky');
    } else {
      this.header.classList.remove('is-sticky');
    }
  },

  /**
   * Setup mobile navigation
   */
  setupMobileNav() {
    // Clone main navigation for mobile
    const mainNav = document.querySelector('.primary-menu');
    
    if (mainNav && this.mobileNav) {
      const mobileNavBody = this.mobileNav.querySelector('.mobile-nav__body');
      
      if (mobileNavBody) {
        const mobileMenu = mainNav.cloneNode(true);
        mobileMenu.classList.add('mobile-nav__menu');
        mobileNavBody.appendChild(mobileMenu);
      }
    }
  },

  /**
   * Setup dropdown menus
   */
  setupDropdowns() {
    this.dropdownMenus.forEach(item => {
      // Create dropdown toggle button
      const dropdownToggle = document.createElement('button');
      dropdownToggle.className = 'dropdown-toggle';
      dropdownToggle.setAttribute('aria-expanded', 'false');
      dropdownToggle.innerHTML = '<span class="screen-reader-text">Toggle Dropdown</span>';
      
      // Add toggle button to menu item
      item.appendChild(dropdownToggle);
      
      // Toggle dropdown on click
      dropdownToggle.addEventListener('click', event => {
        event.preventDefault();
        event.stopPropagation();
        
        const expanded = dropdownToggle.getAttribute('aria-expanded') === 'true';
        dropdownToggle.setAttribute('aria-expanded', !expanded);
        
        const subMenu = item.querySelector('.sub-menu');
        if (subMenu) {
          if (expanded) {
            subMenu.classList.remove('active');
            // Animate height to 0
            subMenu.style.height = subMenu.scrollHeight + 'px';
            setTimeout(() => {
              subMenu.style.height = '0px';
            }, 10);
          } else {
            subMenu.classList.add('active');
            // Animate height to auto
            subMenu.style.height = '0px';
            setTimeout(() => {
              subMenu.style.height = subMenu.scrollHeight + 'px';
              // Remove height after transition
              setTimeout(() => {
                subMenu.style.height = 'auto';
              }, 300);
            }, 10);
          }
        }
      });
    });
  },

  /**
   * Toggle mobile navigation
   */
  toggleMobileNav() {
    if (this.mobileNav) {
      this.mobileNav.classList.toggle('active');
      this.mobileToggle.setAttribute(
        'aria-expanded',
        this.mobileNav.classList.contains('active')
      );
      
      // Prevent body scrolling when mobile nav is open
      if (this.mobileNav.classList.contains('active')) {
        this.body.classList.add('mobile-nav-active');
      } else {
        this.body.classList.remove('mobile-nav-active');
      }
    }
  },

  /**
   * Close mobile navigation
   */
  closeMobileNav() {
    if (this.mobileNav) {
      this.mobileNav.classList.remove('active');
      this.mobileToggle.setAttribute('aria-expanded', 'false');
      this.body.classList.remove('mobile-nav-active');
    }
  }
};

export default Navigation;