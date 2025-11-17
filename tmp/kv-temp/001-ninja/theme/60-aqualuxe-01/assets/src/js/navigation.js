/**
 * AquaLuxe Theme Navigation JavaScript
 * 
 * This file contains the navigation functionality for the AquaLuxe theme.
 * 
 * @package AquaLuxe
 * @since 1.0.0
 */

(function($) {
    'use strict';

    const AquaLuxeNavigation = {
        /**
         * Initialize navigation
         */
        init: function() {
            this.setupMobileMenu();
            this.setupDropdowns();
            this.setupMegaMenus();
            this.setupAccessibility();
            this.setupScrollEffects();
        },

        /**
         * Setup mobile menu
         */
        setupMobileMenu: function() {
            const $menuToggle = $('.menu-toggle');
            const $mobileMenu = $('.main-navigation');
            const $body = $('body');
            
            // Toggle mobile menu
            $menuToggle.on('click', function(e) {
                e.preventDefault();
                
                $(this).toggleClass('active');
                $mobileMenu.toggleClass('active');
                $body.toggleClass('mobile-menu-active');
                
                // Toggle aria-expanded attribute
                const isExpanded = $(this).hasClass('active');
                $(this).attr('aria-expanded', isExpanded);
                
                // Trap focus within mobile menu when open
                if (isExpanded) {
                    AquaLuxeNavigation.trapFocus($mobileMenu);
                } else {
                    AquaLuxeNavigation.releaseFocus();
                }
            });
            
            // Close menu when clicking outside
            $(document).on('click', function(event) {
                if ($body.hasClass('mobile-menu-active') && 
                    !$(event.target).closest('.main-navigation, .menu-toggle').length) {
                    $menuToggle.removeClass('active');
                    $mobileMenu.removeClass('active');
                    $body.removeClass('mobile-menu-active');
                    $menuToggle.attr('aria-expanded', false);
                    AquaLuxeNavigation.releaseFocus();
                }
            });
            
            // Handle sub-menu toggles on mobile
            if (!$('.sub-menu-toggle').length) {
                $('.menu-item-has-children > a').append('<span class="sub-menu-toggle" tabindex="0" role="button" aria-expanded="false"><span class="screen-reader-text">Toggle submenu</span></span>');
            }
            
            $(document).on('click keypress', '.sub-menu-toggle', function(e) {
                if (e.type === 'keypress' && e.which !== 13 && e.which !== 32) {
                    return;
                }
                
                e.preventDefault();
                e.stopPropagation();
                
                const $parent = $(this).closest('.menu-item-has-children');
                const $subMenu = $parent.find('> .sub-menu');
                const isExpanded = $parent.hasClass('sub-menu-open');
                
                $parent.toggleClass('sub-menu-open');
                $(this).attr('aria-expanded', !isExpanded);
                
                if (isExpanded) {
                    $subMenu.slideUp(200);
                } else {
                    $subMenu.slideDown(200);
                }
            });
            
            // Close other submenus when opening a new one on mobile
            $(document).on('click keypress', '.menu-item-has-children > a > .sub-menu-toggle', function() {
                const $parent = $(this).closest('.menu-item-has-children');
                const $siblings = $parent.siblings('.menu-item-has-children.sub-menu-open');
                
                if ($siblings.length && window.innerWidth < 992) {
                    $siblings.removeClass('sub-menu-open');
                    $siblings.find('> .sub-menu').slideUp(200);
                    $siblings.find('> a > .sub-menu-toggle').attr('aria-expanded', false);
                }
            });
            
            // Handle window resize
            $(window).on('resize', function() {
                if (window.innerWidth >= 992 && $body.hasClass('mobile-menu-active')) {
                    $menuToggle.removeClass('active');
                    $mobileMenu.removeClass('active');
                    $body.removeClass('mobile-menu-active');
                    $menuToggle.attr('aria-expanded', false);
                    AquaLuxeNavigation.releaseFocus();
                }
            });
        },

        /**
         * Setup dropdown menus
         */
        setupDropdowns: function() {
            // Add dropdown functionality for desktop
            $('.menu-item-has-children').on({
                mouseenter: function() {
                    if (window.innerWidth >= 992) {
                        $(this).addClass('sub-menu-open');
                        $(this).find('> a > .sub-menu-toggle').attr('aria-expanded', true);
                    }
                },
                mouseleave: function() {
                    if (window.innerWidth >= 992) {
                        $(this).removeClass('sub-menu-open');
                        $(this).find('> a > .sub-menu-toggle').attr('aria-expanded', false);
                    }
                }
            });
            
            // Accessibility - keyboard navigation
            $('.menu-item-has-children > a').on('focus', function() {
                if (window.innerWidth >= 992) {
                    $(this).parent().addClass('sub-menu-open');
                    $(this).find('> .sub-menu-toggle').attr('aria-expanded', true);
                }
            });
            
            $('.menu-item-has-children').on('focusout', function(e) {
                if (window.innerWidth >= 992 && !$(this).has(e.relatedTarget).length) {
                    $(this).removeClass('sub-menu-open');
                    $(this).find('> a > .sub-menu-toggle').attr('aria-expanded', false);
                }
            });
        },

        /**
         * Setup mega menus
         */
        setupMegaMenus: function() {
            // Add mega menu functionality
            $('.mega-menu').each(function() {
                const $megaMenu = $(this);
                const $subMenu = $megaMenu.find('> .sub-menu');
                
                // Position mega menu
                if (window.innerWidth >= 992) {
                    const menuWidth = $subMenu.outerWidth();
                    const windowWidth = $(window).width();
                    const menuOffset = $megaMenu.offset().left;
                    const menuPosition = menuOffset + menuWidth;
                    
                    if (menuPosition > windowWidth) {
                        $subMenu.css({
                            'left': 'auto',
                            'right': '0'
                        });
                    }
                }
                
                // Handle window resize
                $(window).on('resize', function() {
                    if (window.innerWidth >= 992) {
                        $subMenu.css({
                            'left': '',
                            'right': ''
                        });
                        
                        const menuWidth = $subMenu.outerWidth();
                        const windowWidth = $(window).width();
                        const menuOffset = $megaMenu.offset().left;
                        const menuPosition = menuOffset + menuWidth;
                        
                        if (menuPosition > windowWidth) {
                            $subMenu.css({
                                'left': 'auto',
                                'right': '0'
                            });
                        }
                    }
                });
            });
        },

        /**
         * Setup accessibility features
         */
        setupAccessibility: function() {
            // Add ARIA attributes to menu items
            $('.menu-item-has-children').attr('aria-haspopup', 'true');
            
            // Add screen reader text
            $('.menu-item-has-children > a').each(function() {
                const menuText = $(this).text();
                $(this).find('.sub-menu-toggle .screen-reader-text').text('Toggle ' + menuText + ' submenu');
            });
            
            // Skip link focus fix
            $('#primary').on('focus', function() {
                $(this).removeClass('skip-link-focus-fix');
            });
        },

        /**
         * Setup scroll effects
         */
        setupScrollEffects: function() {
            // Add active class to menu items on scroll
            if ($('.single-post, .single-page').length) {
                $(window).on('scroll', function() {
                    const scrollPosition = $(window).scrollTop();
                    
                    $('.site-content h2[id], .site-content h3[id]').each(function() {
                        const currentSection = $(this);
                        const sectionTop = currentSection.offset().top - 100;
                        const sectionId = currentSection.attr('id');
                        
                        if (scrollPosition >= sectionTop) {
                            $('.main-navigation a').removeClass('active');
                            $('.main-navigation a[href="#' + sectionId + '"]').addClass('active');
                        }
                    });
                });
            }
            
            // Smooth scroll to anchor links
            $('a[href*="#"]:not([href="#"])').on('click', function() {
                if (location.pathname.replace(/^\//, '') === this.pathname.replace(/^\//, '') && 
                    location.hostname === this.hostname) {
                    
                    let target = $(this.hash);
                    target = target.length ? target : $('[name=' + this.hash.slice(1) + ']');
                    
                    if (target.length) {
                        $('html, body').animate({
                            scrollTop: target.offset().top - 80
                        }, 500);
                        
                        // Update URL hash without jumping
                        if (history.pushState) {
                            history.pushState(null, null, this.hash);
                        } else {
                            location.hash = this.hash;
                        }
                        
                        return false;
                    }
                }
            });
        },

        /**
         * Trap focus within an element
         * 
         * @param {jQuery} $container The container to trap focus within
         */
        trapFocus: function($container) {
            // Find all focusable elements
            const $focusableElements = $container.find('a, button, input, select, textarea, [tabindex]:not([tabindex="-1"])');
            const $firstFocusable = $focusableElements.first();
            const $lastFocusable = $focusableElements.last();
            
            // Store the element that had focus before opening
            AquaLuxeNavigation.focusedElementBeforeTrap = document.activeElement;
            
            // Focus the first element
            $firstFocusable.focus();
            
            // Trap focus
            $container.on('keydown.trapFocus', function(e) {
                if (e.key === 'Tab' || e.keyCode === 9) {
                    if (e.shiftKey) {
                        // Shift + Tab
                        if (document.activeElement === $firstFocusable[0]) {
                            e.preventDefault();
                            $lastFocusable.focus();
                        }
                    } else {
                        // Tab
                        if (document.activeElement === $lastFocusable[0]) {
                            e.preventDefault();
                            $firstFocusable.focus();
                        }
                    }
                }
                
                // Close on Escape
                if (e.key === 'Escape' || e.keyCode === 27) {
                    $('.menu-toggle').trigger('click');
                }
            });
        },

        /**
         * Release focus trap
         */
        releaseFocus: function() {
            // Remove event handler
            $(document).off('keydown.trapFocus');
            
            // Restore focus
            if (AquaLuxeNavigation.focusedElementBeforeTrap) {
                $(AquaLuxeNavigation.focusedElementBeforeTrap).focus();
            }
        }
    };

    // Initialize when document is ready
    $(document).ready(function() {
        AquaLuxeNavigation.init();
    });

})(jQuery);