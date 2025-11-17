/**
 * Navigation Module
 * 
 * Handles the navigation functionality including mobile menu, mega menu,
 * dropdown menus, and sticky header.
 */

// Navigation initialization
export function initNavigation() {
  // Initialize mobile menu
  initMobileMenu();
  
  // Initialize mega menu
  initMegaMenu();
  
  // Initialize dropdown menus
  initDropdownMenus();
  
  // Initialize sticky header
  initStickyHeader();
}

// Initialize mobile menu
function initMobileMenu() {
  const mobileMenuToggle = document.getElementById('mobile-menu-toggle');
  const mobileMenu = document.getElementById('mobile-menu');
  const mobileMenuClose = document.getElementById('mobile-menu-close');
  const mobileMenuOverlay = document.getElementById('mobile-menu-overlay');
  
  if (!mobileMenuToggle || !mobileMenu) return;
  
  // Toggle mobile menu
  mobileMenuToggle.addEventListener('click', function() {
    mobileMenu.classList.toggle('hidden');
    document.body.classList.toggle('mobile-menu-open');
    
    if (mobileMenuOverlay) {
      mobileMenuOverlay.classList.toggle('hidden');
    }
    
    // Set aria attributes
    const isExpanded = mobileMenu.classList.contains('hidden') ? 'false' : 'true';
    mobileMenuToggle.setAttribute('aria-expanded', isExpanded);
  });
  
  // Close mobile menu
  if (mobileMenuClose) {
    mobileMenuClose.addEventListener('click', function() {
      mobileMenu.classList.add('hidden');
      document.body.classList.remove('mobile-menu-open');
      
      if (mobileMenuOverlay) {
        mobileMenuOverlay.classList.add('hidden');
      }
      
      mobileMenuToggle.setAttribute('aria-expanded', 'false');
    });
  }
  
  // Close mobile menu when clicking on overlay
  if (mobileMenuOverlay) {
    mobileMenuOverlay.addEventListener('click', function() {
      mobileMenu.classList.add('hidden');
      document.body.classList.remove('mobile-menu-open');
      mobileMenuOverlay.classList.add('hidden');
      mobileMenuToggle.setAttribute('aria-expanded', 'false');
    });
  }
  
  // Handle submenu toggles in mobile menu
  const mobileSubMenuToggles = document.querySelectorAll('.mobile-submenu-toggle');
  
  mobileSubMenuToggles.forEach(toggle => {
    toggle.addEventListener('click', function(e) {
      e.preventDefault();
      
      const parent = this.closest('li');
      const submenu = parent.querySelector('ul');
      
      if (submenu) {
        submenu.classList.toggle('hidden');
        this.setAttribute('aria-expanded', submenu.classList.contains('hidden') ? 'false' : 'true');
      }
    });
  });
}

// Initialize mega menu
function initMegaMenu() {
  const megaMenuItems = document.querySelectorAll('.has-mega-menu');
  
  megaMenuItems.forEach(item => {
    const megaMenu = item.querySelector('.mega-menu');
    const link = item.querySelector('a');
    
    if (!megaMenu || !link) return;
    
    // Show mega menu on hover
    item.addEventListener('mouseenter', function() {
      megaMenu.classList.remove('hidden');
      link.setAttribute('aria-expanded', 'true');
    });
    
    // Hide mega menu on mouse leave
    item.addEventListener('mouseleave', function() {
      megaMenu.classList.add('hidden');
      link.setAttribute('aria-expanded', 'false');
    });
    
    // Toggle mega menu on click for touch devices
    link.addEventListener('click', function(e) {
      if (window.innerWidth >= 1024) { // Only for desktop
        if (megaMenu.classList.contains('hidden')) {
          e.preventDefault();
          
          // Hide all other mega menus
          document.querySelectorAll('.mega-menu').forEach(menu => {
            if (menu !== megaMenu) {
              menu.classList.add('hidden');
              const parentLink = menu.closest('li').querySelector('a');
              if (parentLink) {
                parentLink.setAttribute('aria-expanded', 'false');
              }
            }
          });
          
          megaMenu.classList.remove('hidden');
          link.setAttribute('aria-expanded', 'true');
        }
      }
    });
  });
  
  // Close mega menus when clicking outside
  document.addEventListener('click', function(e) {
    if (!e.target.closest('.has-mega-menu')) {
      document.querySelectorAll('.mega-menu').forEach(menu => {
        menu.classList.add('hidden');
        const parentLink = menu.closest('li').querySelector('a');
        if (parentLink) {
          parentLink.setAttribute('aria-expanded', 'false');
        }
      });
    }
  });
}

// Initialize dropdown menus
function initDropdownMenus() {
  const dropdownItems = document.querySelectorAll('.has-dropdown');
  
  dropdownItems.forEach(item => {
    const dropdown = item.querySelector('.dropdown');
    const link = item.querySelector('a');
    
    if (!dropdown || !link) return;
    
    // Show dropdown on hover
    item.addEventListener('mouseenter', function() {
      dropdown.classList.remove('hidden');
      link.setAttribute('aria-expanded', 'true');
    });
    
    // Hide dropdown on mouse leave
    item.addEventListener('mouseleave', function() {
      dropdown.classList.add('hidden');
      link.setAttribute('aria-expanded', 'false');
    });
    
    // Toggle dropdown on click for touch devices
    link.addEventListener('click', function(e) {
      if (window.innerWidth >= 1024) { // Only for desktop
        if (dropdown.classList.contains('hidden')) {
          e.preventDefault();
          
          // Hide all other dropdowns
          document.querySelectorAll('.dropdown').forEach(menu => {
            if (menu !== dropdown) {
              menu.classList.add('hidden');
              const parentLink = menu.closest('li').querySelector('a');
              if (parentLink) {
                parentLink.setAttribute('aria-expanded', 'false');
              }
            }
          });
          
          dropdown.classList.remove('hidden');
          link.setAttribute('aria-expanded', 'true');
        }
      }
    });
  });
  
  // Close dropdowns when clicking outside
  document.addEventListener('click', function(e) {
    if (!e.target.closest('.has-dropdown')) {
      document.querySelectorAll('.dropdown').forEach(menu => {
        menu.classList.add('hidden');
        const parentLink = menu.closest('li').querySelector('a');
        if (parentLink) {
          parentLink.setAttribute('aria-expanded', 'false');
        }
      });
    }
  });
}

// Initialize sticky header
function initStickyHeader() {
  const header = document.getElementById('site-header');
  if (!header) return;
  
  const headerHeight = header.offsetHeight;
  let lastScrollTop = 0;
  const scrollThreshold = 50;
  
  window.addEventListener('scroll', function() {
    const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
    
    // Add sticky class when scrolling down past threshold
    if (scrollTop > headerHeight) {
      header.classList.add('sticky-header');
      document.body.style.paddingTop = headerHeight + 'px';
      
      // Hide header when scrolling down, show when scrolling up
      if (scrollTop > lastScrollTop && scrollTop > headerHeight + scrollThreshold) {
        header.classList.add('header-hidden');
      } else {
        header.classList.remove('header-hidden');
      }
    } else {
      header.classList.remove('sticky-header');
      document.body.style.paddingTop = '0';
    }
    
    lastScrollTop = scrollTop;
  });
}

// Export the function for use in other modules
export default initNavigation;