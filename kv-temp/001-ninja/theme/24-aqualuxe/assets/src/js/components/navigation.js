/**
 * Navigation functionality
 * 
 * Handles mobile menu toggle, dropdown menus, and search functionality
 */

export default (function() {
  // Initialize navigation when DOM is ready
  document.addEventListener('DOMContentLoaded', function() {
    initMobileMenu();
    initDropdownMenus();
    initSearchToggle();
  });

  /**
   * Initialize mobile menu functionality
   */
  function initMobileMenu() {
    const mobileMenuToggle = document.getElementById('mobile-menu-toggle');
    const mobileMenu = document.getElementById('mobile-menu');
    const siteNavigation = document.getElementById('site-navigation');
    
    if (!mobileMenuToggle || !mobileMenu) {
      return;
    }
    
    // Toggle mobile menu
    mobileMenuToggle.addEventListener('click', function() {
      mobileMenu.classList.toggle('hidden');
      
      // Update aria-expanded attribute
      const isExpanded = mobileMenuToggle.getAttribute('aria-expanded') === 'true';
      mobileMenuToggle.setAttribute('aria-expanded', !isExpanded);
      
      // Toggle body class to prevent scrolling
      document.body.classList.toggle('mobile-menu-open');
    });
    
    // Close mobile menu when clicking outside
    document.addEventListener('click', function(event) {
      const isClickInside = siteNavigation.contains(event.target) || mobileMenuToggle.contains(event.target);
      
      if (!isClickInside && !mobileMenu.classList.contains('hidden')) {
        mobileMenu.classList.add('hidden');
        mobileMenuToggle.setAttribute('aria-expanded', 'false');
        document.body.classList.remove('mobile-menu-open');
      }
    });
    
    // Close mobile menu on ESC key
    document.addEventListener('keydown', function(event) {
      if (event.key === 'Escape' && !mobileMenu.classList.contains('hidden')) {
        mobileMenu.classList.add('hidden');
        mobileMenuToggle.setAttribute('aria-expanded', 'false');
        document.body.classList.remove('mobile-menu-open');
        mobileMenuToggle.focus();
      }
    });
    
    // Handle submenus in mobile menu
    const mobileSubMenuToggles = mobileMenu.querySelectorAll('.menu-item-has-children > a');
    
    mobileSubMenuToggles.forEach(toggle => {
      // Create submenu toggle button
      const toggleButton = document.createElement('button');
      toggleButton.className = 'submenu-toggle';
      toggleButton.setAttribute('aria-expanded', 'false');
      toggleButton.innerHTML = '<span class="screen-reader-text">Toggle submenu</span><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" /></svg>';
      
      toggle.parentNode.insertBefore(toggleButton, toggle.nextSibling);
      
      // Toggle submenu on button click
      toggleButton.addEventListener('click', function(e) {
        e.preventDefault();
        
        const submenu = this.parentNode.querySelector('.sub-menu');
        submenu.classList.toggle('hidden');
        
        // Update aria-expanded attribute
        const isExpanded = this.getAttribute('aria-expanded') === 'true';
        this.setAttribute('aria-expanded', !isExpanded);
        
        // Toggle icon rotation
        this.classList.toggle('rotate');
      });
    });
  }
  
  /**
   * Initialize dropdown menus for desktop navigation
   */
  function initDropdownMenus() {
    const menuItems = document.querySelectorAll('.main-navigation .menu-item-has-children');
    
    menuItems.forEach(item => {
      // Add aria attributes
      const link = item.querySelector('a');
      const submenu = item.querySelector('.sub-menu');
      
      if (link && submenu) {
        link.setAttribute('aria-haspopup', 'true');
        link.setAttribute('aria-expanded', 'false');
        submenu.setAttribute('aria-hidden', 'true');
        
        // Toggle submenu on hover for desktop
        if (window.innerWidth > 1024) {
          item.addEventListener('mouseenter', function() {
            submenu.classList.remove('hidden');
            link.setAttribute('aria-expanded', 'true');
            submenu.setAttribute('aria-hidden', 'false');
          });
          
          item.addEventListener('mouseleave', function() {
            submenu.classList.add('hidden');
            link.setAttribute('aria-expanded', 'false');
            submenu.setAttribute('aria-hidden', 'true');
          });
        }
        
        // Handle keyboard navigation
        link.addEventListener('keydown', function(e) {
          if (e.key === 'Enter' || e.key === ' ') {
            e.preventDefault();
            
            const isExpanded = link.getAttribute('aria-expanded') === 'true';
            
            if (isExpanded) {
              submenu.classList.add('hidden');
              link.setAttribute('aria-expanded', 'false');
              submenu.setAttribute('aria-hidden', 'true');
            } else {
              submenu.classList.remove('hidden');
              link.setAttribute('aria-expanded', 'true');
              submenu.setAttribute('aria-hidden', 'false');
              
              // Focus first item in submenu
              const firstItem = submenu.querySelector('a');
              if (firstItem) {
                firstItem.focus();
              }
            }
          }
        });
      }
    });
    
    // Close submenus when pressing ESC
    document.addEventListener('keydown', function(e) {
      if (e.key === 'Escape') {
        const expandedItems = document.querySelectorAll('.main-navigation [aria-expanded="true"]');
        
        expandedItems.forEach(item => {
          const submenu = item.parentNode.querySelector('.sub-menu');
          
          if (submenu) {
            submenu.classList.add('hidden');
            item.setAttribute('aria-expanded', 'false');
            submenu.setAttribute('aria-hidden', 'true');
            item.focus();
          }
        });
      }
    });
  }
  
  /**
   * Initialize search toggle functionality
   */
  function initSearchToggle() {
    const searchToggle = document.getElementById('header-search-toggle');
    const searchForm = document.getElementById('header-search');
    
    if (!searchToggle || !searchForm) {
      return;
    }
    
    // Toggle search form
    searchToggle.addEventListener('click', function(e) {
      e.preventDefault();
      
      searchForm.classList.toggle('hidden');
      
      // Focus search input when showing form
      if (!searchForm.classList.contains('hidden')) {
        const searchInput = searchForm.querySelector('input[type="search"]');
        if (searchInput) {
          searchInput.focus();
        }
      }
      
      // Update aria-expanded attribute
      const isExpanded = searchToggle.getAttribute('aria-expanded') === 'true';
      searchToggle.setAttribute('aria-expanded', !isExpanded);
    });
    
    // Close search form when clicking outside
    document.addEventListener('click', function(e) {
      const isClickInside = searchToggle.contains(e.target) || searchForm.contains(e.target);
      
      if (!isClickInside && !searchForm.classList.contains('hidden')) {
        searchForm.classList.add('hidden');
        searchToggle.setAttribute('aria-expanded', 'false');
      }
    });
    
    // Close search form on ESC key
    document.addEventListener('keydown', function(e) {
      if (e.key === 'Escape' && !searchForm.classList.contains('hidden')) {
        searchForm.classList.add('hidden');
        searchToggle.setAttribute('aria-expanded', 'false');
        searchToggle.focus();
      }
    });
  }
})();