/**
 * AquaLuxe WordPress Theme
 * Navigation Module
 */

/**
 * File navigation.js.
 *
 * Handles toggling the navigation menu for small screens and enables TAB key
 * navigation support for dropdown menus.
 */
(function() {
  const siteNavigation = document.getElementById('site-navigation');

  // Return early if the navigation doesn't exist.
  if (!siteNavigation) {
    return;
  }

  const button = siteNavigation.querySelector('.menu-toggle');

  // Return early if the button doesn't exist.
  if (!button) {
    return;
  }

  const menu = siteNavigation.querySelector('.primary-menu');

  // Hide menu toggle button if menu is empty and return early.
  if (!menu) {
    button.style.display = 'none';
    return;
  }

  if (!menu.classList.contains('nav-menu')) {
    menu.classList.add('nav-menu');
  }

  // Toggle the .toggled class and the aria-expanded value each time the button is clicked.
  button.addEventListener('click', function() {
    siteNavigation.classList.toggle('toggled');

    if (button.getAttribute('aria-expanded') === 'true') {
      button.setAttribute('aria-expanded', 'false');
    } else {
      button.setAttribute('aria-expanded', 'true');
    }
  });

  // Remove the .toggled class and set aria-expanded to false when the user clicks outside the navigation.
  document.addEventListener('click', function(event) {
    const isClickInside = siteNavigation.contains(event.target);

    if (!isClickInside) {
      siteNavigation.classList.remove('toggled');
      button.setAttribute('aria-expanded', 'false');
    }
  });

  // Get all the link elements within the menu.
  const links = menu.getElementsByTagName('a');

  // Get all the link elements with children within the menu.
  const linksWithChildren = menu.querySelectorAll('.menu-item-has-children > a, .page_item_has_children > a');

  // Toggle focus each time a menu link is focused or blurred.
  for (const link of links) {
    link.addEventListener('focus', toggleFocus, true);
    link.addEventListener('blur', toggleFocus, true);
  }

  // Toggle focus each time a menu link with children receive a touch event.
  for (const link of linksWithChildren) {
    link.addEventListener('touchstart', toggleFocus, false);
  }

  /**
   * Sets or removes .focus class on an element.
   */
  function toggleFocus() {
    if (event.type === 'focus' || event.type === 'blur') {
      let self = this;
      // Move up through the ancestors of the current link until we hit .nav-menu.
      while (!self.classList.contains('nav-menu')) {
        // On li elements toggle the class .focus.
        if ('li' === self.tagName.toLowerCase()) {
          self.classList.toggle('focus');
        }
        self = self.parentNode;
      }
    }

    if (event.type === 'touchstart') {
      const menuItem = this.parentNode;
      event.preventDefault();
      for (const link of menuItem.parentNode.children) {
        if (menuItem !== link) {
          link.classList.remove('focus');
        }
      }
      menuItem.classList.toggle('focus');
    }
  }

  // Mobile Menu Dropdown Toggle
  const dropdownToggles = document.querySelectorAll('.dropdown-toggle');
  
  dropdownToggles.forEach(toggle => {
    toggle.addEventListener('click', function(e) {
      e.preventDefault();
      const menuItem = this.parentNode;
      menuItem.classList.toggle('toggled-on');
      
      if (this.getAttribute('aria-expanded') === 'true') {
        this.setAttribute('aria-expanded', 'false');
      } else {
        this.setAttribute('aria-expanded', 'true');
      }
    });
  });

  // Mega Menu
  const megaMenuItems = document.querySelectorAll('.mega-menu-item');
  
  megaMenuItems.forEach(item => {
    const link = item.querySelector('a');
    const megaMenu = item.querySelector('.mega-menu');
    
    if (link && megaMenu) {
      // Show mega menu on hover
      item.addEventListener('mouseenter', function() {
        megaMenu.classList.add('is-active');
      });
      
      // Hide mega menu on mouse leave
      item.addEventListener('mouseleave', function() {
        megaMenu.classList.remove('is-active');
      });
      
      // Toggle mega menu on click for mobile
      link.addEventListener('click', function(e) {
        if (window.innerWidth < 1024) {
          e.preventDefault();
          megaMenu.classList.toggle('is-active');
        }
      });
    }
  });

  // Sticky Navigation
  const header = document.querySelector('.site-header');
  let lastScrollTop = 0;
  
  if (header) {
    window.addEventListener('scroll', function() {
      const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
      
      if (scrollTop > 100) {
        header.classList.add('is-sticky');
        
        // Hide header on scroll down, show on scroll up
        if (scrollTop > lastScrollTop) {
          header.classList.add('is-hidden');
        } else {
          header.classList.remove('is-hidden');
        }
      } else {
        header.classList.remove('is-sticky', 'is-hidden');
      }
      
      lastScrollTop = scrollTop;
    });
  }
})();