/**
 * Main JavaScript file for AquaLuxe theme
 */

(function($) {
  'use strict';

  // DOM elements
  const $window = $(window);
  const $document = $(document);
  const $body = $('body');
  const $siteHeader = $('.site-header');
  const $mobileMenuToggle = $('#mobile-menu-toggle');
  const $mobileMenu = $('#mobile-menu');
  const $searchToggle = $('#search-toggle');
  const $headerSearchForm = $('#header-search-form');
  const $darkModeToggle = $('#dark-mode-toggle');
  const $backToTopBtn = $('#back-to-top-btn');

  /**
   * Initialize the theme
   */
  function initTheme() {
    setupMobileMenu();
    setupHeaderSearch();
    setupStickyHeader();
    setupDarkMode();
    setupBackToTop();
    setupDropdownMenus();
    setupAccessibility();
  }

  /**
   * Mobile menu functionality
   */
  function setupMobileMenu() {
    if (!$mobileMenuToggle.length || !$mobileMenu.length) return;

    $mobileMenuToggle.on('click', function(e) {
      e.preventDefault();
      $mobileMenu.toggleClass('active');
      $body.toggleClass('mobile-menu-open');
      
      if ($mobileMenu.hasClass('active')) {
        $mobileMenuToggle.attr('aria-expanded', 'true');
      } else {
        $mobileMenuToggle.attr('aria-expanded', 'false');
      }
    });

    // Handle submenu toggles
    $mobileMenu.find('.menu-item-has-children > a').on('click', function(e) {
      const $this = $(this);
      const $parent = $this.parent();
      const $submenu = $parent.find('> .sub-menu');
      
      if ($submenu.length) {
        e.preventDefault();
        $parent.toggleClass('active');
        $submenu.slideToggle(200);
      }
    });

    // Close mobile menu on window resize (if desktop view)
    $window.on('resize', function() {
      if ($window.width() >= 1024 && $mobileMenu.hasClass('active')) {
        $mobileMenu.removeClass('active');
        $body.removeClass('mobile-menu-open');
        $mobileMenuToggle.attr('aria-expanded', 'false');
      }
    });
  }

  /**
   * Header search functionality
   */
  function setupHeaderSearch() {
    if (!$searchToggle.length || !$headerSearchForm.length) return;

    $searchToggle.on('click', function(e) {
      e.preventDefault();
      $headerSearchForm.toggleClass('active');
      
      if ($headerSearchForm.hasClass('active')) {
        $searchToggle.attr('aria-expanded', 'true');
        $headerSearchForm.find('input[type="search"]').focus();
      } else {
        $searchToggle.attr('aria-expanded', 'false');
      }
    });

    // Close search form when clicking outside
    $document.on('click', function(e) {
      if (!$(e.target).closest('.header-search').length && $headerSearchForm.hasClass('active')) {
        $headerSearchForm.removeClass('active');
        $searchToggle.attr('aria-expanded', 'false');
      }
    });
  }

  /**
   * Sticky header functionality
   */
  function setupStickyHeader() {
    if (!$siteHeader.length) return;

    let lastScrollTop = 0;
    const scrollThreshold = 100;

    $window.on('scroll', function() {
      const scrollTop = $window.scrollTop();
      
      // Add shadow when scrolled
      if (scrollTop > 10) {
        $siteHeader.addClass('scrolled');
      } else {
        $siteHeader.removeClass('scrolled');
      }
      
      // Hide/show header on scroll up/down
      if (scrollTop > scrollThreshold) {
        if (scrollTop > lastScrollTop) {
          // Scrolling down
          $siteHeader.addClass('header-hidden');
        } else {
          // Scrolling up
          $siteHeader.removeClass('header-hidden');
        }
      }
      
      lastScrollTop = scrollTop;
    });
  }

  /**
   * Dark mode functionality
   */
  function setupDarkMode() {
    if (!$darkModeToggle.length) return;

    // Check for saved user preference
    const darkModeEnabled = localStorage.getItem('aqualuxe_dark_mode') === 'true';
    
    // Check for system preference if no saved preference
    const prefersDarkMode = window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches;
    
    // Set initial state
    if (darkModeEnabled || (prefersDarkMode && localStorage.getItem('aqualuxe_dark_mode') === null)) {
      $body.addClass('dark');
      localStorage.setItem('aqualuxe_dark_mode', 'true');
    }

    // Toggle dark mode on button click
    $darkModeToggle.on('click', function(e) {
      e.preventDefault();
      $body.toggleClass('dark');
      localStorage.setItem('aqualuxe_dark_mode', $body.hasClass('dark') ? 'true' : 'false');
    });

    // Listen for system preference changes
    if (window.matchMedia) {
      window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', e => {
        // Only change if user hasn't set a preference
        if (localStorage.getItem('aqualuxe_dark_mode') === null) {
          if (e.matches) {
            $body.addClass('dark');
          } else {
            $body.removeClass('dark');
          }
        }
      });
    }
  }

  /**
   * Back to top button functionality
   */
  function setupBackToTop() {
    if (!$backToTopBtn.length) return;

    // Show/hide button based on scroll position
    $window.on('scroll', function() {
      if ($window.scrollTop() > 300) {
        $backToTopBtn.addClass('active');
      } else {
        $backToTopBtn.removeClass('active');
      }
    });

    // Smooth scroll to top when clicked
    $backToTopBtn.on('click', function(e) {
      e.preventDefault();
      $('html, body').animate({ scrollTop: 0 }, 500);
    });
  }

  /**
   * Dropdown menu functionality
   */
  function setupDropdownMenus() {
    // Add aria attributes to dropdown menus
    $('.menu-item-has-children > a').attr('aria-haspopup', 'true');
    
    // Handle keyboard navigation
    $('.main-navigation .menu-item-has-children > a').on('keydown', function(e) {
      if (e.key === 'Enter' || e.key === ' ') {
        e.preventDefault();
        $(this).parent().toggleClass('focus');
        $(this).next('.sub-menu').attr('aria-hidden', function(i, attr) {
          return attr === 'true' ? 'false' : 'true';
        });
      }
    });
    
    // Close dropdowns when clicking outside
    $document.on('click', function(e) {
      if (!$(e.target).closest('.menu-item-has-children').length) {
        $('.menu-item-has-children.focus').removeClass('focus');
        $('.sub-menu').attr('aria-hidden', 'true');
      }
    });
  }

  /**
   * Accessibility improvements
   */
  function setupAccessibility() {
    // Skip link focus fix
    $document.on('click', '.skip-link', function() {
      const target = $(this.hash);
      if (target.length) {
        target.attr('tabindex', '-1');
        target.focus();
        target.on('blur focusout', function() {
          $(this).removeAttr('tabindex');
        });
      }
    });
    
    // Add aria-hidden to decorative icons
    $('.svg-icon').attr('aria-hidden', 'true');
    
    // Ensure all interactive elements are keyboard accessible
    $('a, button, input, select, textarea, [tabindex]').on('keydown', function(e) {
      if (e.key === 'Enter' || e.key === ' ') {
        if (this.tagName !== 'INPUT' && this.tagName !== 'TEXTAREA' && this.tagName !== 'SELECT') {
          e.preventDefault();
          this.click();
        }
      }
    });
  }

  // Initialize when DOM is ready
  $(document).ready(function() {
    initTheme();
  });

})(jQuery);