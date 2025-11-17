/**
 * AquaLuxe Theme - Navigation
 *
 * This file handles the main navigation functionality including:
 * - Mobile menu toggle
 * - Dropdown menus
 * - Accessibility features
 * - Sticky header
 */

(function() {
  'use strict';
  
  // DOM elements
  const header = document.querySelector('.site-header');
  const menuToggle = document.querySelector('.menu-toggle');
  const mobileMenu = document.querySelector('.mobile-menu');
  const siteNavigation = document.querySelector('.main-navigation');
  const dropdownToggles = document.querySelectorAll('.dropdown-toggle');
  
  // Variables
  let isMenuOpen = false;
  let lastScrollTop = 0;
  let isHeaderSticky = false;
  
  /**
   * Initialize navigation functionality
   */
  function init() {
    if (!header || !siteNavigation) {
      return;
    }
    
    setupMobileMenu();
    setupDropdownMenus();
    setupStickyHeader();
    setupAccessibility();
    setupSubMenuPositioning();
  }
  
  /**
   * Set up mobile menu toggle functionality
   */
  function setupMobileMenu() {
    if (!menuToggle || !mobileMenu) {
      return;
    }
    
    // Hide menu initially
    mobileMenu.setAttribute('aria-hidden', 'true');
    
    menuToggle.addEventListener('click', function() {
      isMenuOpen = !isMenuOpen;
      
      // Toggle menu visibility
      menuToggle.setAttribute('aria-expanded', isMenuOpen);
      mobileMenu.setAttribute('aria-hidden', !isMenuOpen);
      
      // Toggle menu toggle button state
      menuToggle.classList.toggle('is-active', isMenuOpen);
      
      // Toggle mobile menu visibility
      mobileMenu.classList.toggle('is-active', isMenuOpen);
      
      // Prevent body scroll when menu is open
      document.body.classList.toggle('menu-open', isMenuOpen);
      
      // Set focus to the first menu item when opened
      if (isMenuOpen) {
        const firstMenuItem = mobileMenu.querySelector('a');
        if (firstMenuItem) {
          setTimeout(() => {
            firstMenuItem.focus();
          }, 100);
        }
      }
    });
    
    // Close mobile menu on window resize if it becomes unnecessary
    window.addEventListener('resize', function() {
      if (window.innerWidth >= 1024 && isMenuOpen) {
        isMenuOpen = false;
        menuToggle.setAttribute('aria-expanded', 'false');
        mobileMenu.setAttribute('aria-hidden', 'true');
        menuToggle.classList.remove('is-active');
        mobileMenu.classList.remove('is-active');
        document.body.classList.remove('menu-open');
      }
    });
    
    // Close mobile menu when clicking outside
    document.addEventListener('click', function(event) {
      if (isMenuOpen && 
          !mobileMenu.contains(event.target) && 
          !menuToggle.contains(event.target)) {
        isMenuOpen = false;
        menuToggle.setAttribute('aria-expanded', 'false');
        mobileMenu.setAttribute('aria-hidden', 'true');
        menuToggle.classList.remove('is-active');
        mobileMenu.classList.remove('is-active');
        document.body.classList.remove('menu-open');
      }
    });
  }
  
  /**
   * Set up dropdown menu functionality
   */
  function setupDropdownMenus() {
    if (!dropdownToggles.length) {
      return;
    }
    
    dropdownToggles.forEach(function(toggle) {
      // Set initial ARIA attributes
      toggle.setAttribute('aria-expanded', 'false');
      
      const parentMenuItem = toggle.parentNode;
      const subMenu = parentMenuItem.querySelector('.sub-menu');
      
      if (subMenu) {
        const menuId = 'submenu-' + Math.random().toString(36).substr(2, 9);
        subMenu.setAttribute('id', menuId);
        toggle.setAttribute('aria-controls', menuId);
        
        // Toggle submenu on click
        toggle.addEventListener('click', function(event) {
          event.preventDefault();
          event.stopPropagation();
          
          const expanded = toggle.getAttribute('aria-expanded') === 'true';
          
          // Close all other submenus at the same level
          const siblingToggles = parentMenuItem.parentNode.querySelectorAll('.dropdown-toggle');
          siblingToggles.forEach(function(siblingToggle) {
            if (siblingToggle !== toggle) {
              siblingToggle.setAttribute('aria-expanded', 'false');
              const siblingSubMenu = siblingToggle.parentNode.querySelector('.sub-menu');
              if (siblingSubMenu) {
                siblingSubMenu.classList.remove('is-active');
              }
            }
          });
          
          // Toggle current submenu
          toggle.setAttribute('aria-expanded', !expanded);
          subMenu.classList.toggle('is-active', !expanded);
          
          // Set focus to the first menu item when opened
          if (!expanded) {
            const firstMenuItem = subMenu.querySelector('a');
            if (firstMenuItem) {
              setTimeout(() => {
                firstMenuItem.focus();
              }, 100);
            }
          }
        });
      }
    });
    
    // Close dropdowns when clicking outside
    document.addEventListener('click', function(event) {
      if (!event.target.closest('.menu-item-has-children')) {
        dropdownToggles.forEach(function(toggle) {
          toggle.setAttribute('aria-expanded', 'false');
          const subMenu = toggle.parentNode.querySelector('.sub-menu');
          if (subMenu) {
            subMenu.classList.remove('is-active');
          }
        });
      }
    });
    
    // Close dropdowns when pressing Escape
    document.addEventListener('keyup', function(event) {
      if (event.key === 'Escape') {
        dropdownToggles.forEach(function(toggle) {
          toggle.setAttribute('aria-expanded', 'false');
          const subMenu = toggle.parentNode.querySelector('.sub-menu');
          if (subMenu) {
            subMenu.classList.remove('is-active');
          }
        });
        
        // If mobile menu is open, close it
        if (isMenuOpen && menuToggle) {
          menuToggle.click();
        }
      }
    });
  }
  
  /**
   * Set up sticky header functionality
   */
  function setupStickyHeader() {
    if (!header || !header.classList.contains('sticky-header')) {
      return;
    }
    
    const headerHeight = header.offsetHeight;
    const headerPlaceholder = document.createElement('div');
    headerPlaceholder.style.height = headerHeight + 'px';
    headerPlaceholder.style.display = 'none';
    header.parentNode.insertBefore(headerPlaceholder, header);
    
    window.addEventListener('scroll', function() {
      const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
      
      // Add sticky class when scrolling down past header height
      if (scrollTop > headerHeight) {
        if (!isHeaderSticky) {
          header.classList.add('is-sticky');
          headerPlaceholder.style.display = 'block';
          isHeaderSticky = true;
        }
        
        // Hide header when scrolling down, show when scrolling up
        if (scrollTop > lastScrollTop && scrollTop > headerHeight * 2) {
          header.classList.add('is-hidden');
        } else {
          header.classList.remove('is-hidden');
        }
      } else {
        header.classList.remove('is-sticky', 'is-hidden');
        headerPlaceholder.style.display = 'none';
        isHeaderSticky = false;
      }
      
      lastScrollTop = scrollTop;
    });
  }
  
  /**
   * Set up accessibility features
   */
  function setupAccessibility() {
    // Add aria-current="page" to current menu item
    const currentMenuItems = document.querySelectorAll('.current-menu-item > a, .current_page_item > a');
    currentMenuItems.forEach(function(link) {
      link.setAttribute('aria-current', 'page');
    });
    
    // Keyboard navigation for dropdown menus
    const menuItems = document.querySelectorAll('.menu-item a');
    menuItems.forEach(function(menuItem) {
      menuItem.addEventListener('keydown', function(event) {
        const parentMenuItem = menuItem.parentNode;
        
        // Open submenu on arrow down/right
        if ((event.key === 'ArrowDown' || event.key === 'ArrowRight') && 
            parentMenuItem.classList.contains('menu-item-has-children')) {
          event.preventDefault();
          
          const toggle = parentMenuItem.querySelector('.dropdown-toggle');
          if (toggle && toggle.getAttribute('aria-expanded') === 'false') {
            toggle.click();
          }
        }
        
        // Close submenu on arrow up/left
        if ((event.key === 'ArrowUp' || event.key === 'ArrowLeft') && 
            parentMenuItem.parentNode.classList.contains('sub-menu')) {
          event.preventDefault();
          
          const parentToggle = parentMenuItem.parentNode.parentNode.querySelector('.dropdown-toggle');
          if (parentToggle && parentToggle.getAttribute('aria-expanded') === 'true') {
            parentToggle.click();
            parentToggle.focus();
          }
        }
      });
    });
  }
  
  /**
   * Set up submenu positioning to prevent off-screen display
   */
  function setupSubMenuPositioning() {
    const subMenus = document.querySelectorAll('.sub-menu');
    
    function positionSubmenus() {
      subMenus.forEach(function(subMenu) {
        // Reset position first
        subMenu.style.left = '';
        subMenu.style.right = '';
        
        // Check if submenu is off-screen
        const rect = subMenu.getBoundingClientRect();
        const viewportWidth = window.innerWidth || document.documentElement.clientWidth;
        
        if (rect.right > viewportWidth) {
          // If off-screen to the right, align to the right
          subMenu.style.left = 'auto';
          subMenu.style.right = '0';
        }
      });
    }
    
    // Position on load and resize
    positionSubmenus();
    window.addEventListener('resize', positionSubmenus);
  }
  
  // Initialize when DOM is ready
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }
})();