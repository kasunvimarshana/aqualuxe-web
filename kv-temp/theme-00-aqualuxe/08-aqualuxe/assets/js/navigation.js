/**
 * Navigation JavaScript for AquaLuxe Theme
 *
 * @package AquaLuxe
 * @version 1.0.0
 */

(function($) {
    'use strict';

    var AquaLuxeNavigation = {
        
        /**
         * Initialize navigation functions
         */
        init: function() {
            this.mobileMenu();
            this.dropdownMenus();
            this.megaMenu();
            this.stickyHeader();
            this.searchFunctionality();
            this.keyboardNavigation();
        },

        /**
         * Mobile menu functionality
         */
        mobileMenu: function() {
            var $menuToggle = $('.menu-toggle');
            var $navigation = $('.main-navigation');
            var $mobileNav = $('.mobile-navigation');
            var $body = $('body');

            // Toggle mobile menu
            $menuToggle.on('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                
                var isExpanded = $(this).attr('aria-expanded') === 'true';
                
                $(this).attr('aria-expanded', !isExpanded);
                $(this).toggleClass('active');
                $navigation.toggleClass('toggled');
                $mobileNav.toggleClass('active');
                $body.toggleClass('menu-open');
                
                // Focus management
                if (!isExpanded) {
                    $navigation.find('a').first().focus();
                }
            });

            // Handle submenu toggles in mobile
            $('.main-navigation .menu-item-has-children > a, .mobile-navigation .menu-item-has-children > a').on('click', function(e) {
                if ($(window).width() < 768) {
                    e.preventDefault();
                    
                    var $parent = $(this).parent();
                    var $submenu = $(this).next('.sub-menu');
                    
                    $parent.toggleClass('open');
                    $submenu.slideToggle(300);
                    
                    // Update aria-expanded
                    var isExpanded = $(this).attr('aria-expanded') === 'true';
                    $(this).attr('aria-expanded', !isExpanded);
                }
            });

            // Close menu when clicking outside
            $(document).on('click', function(e) {
                if (!$(e.target).closest('.site-header').length && 
                    !$(e.target).closest('.mobile-navigation').length) {
                    $menuToggle.attr('aria-expanded', 'false').removeClass('active');
                    $navigation.removeClass('toggled');
                    $mobileNav.removeClass('active');
                    $body.removeClass('menu-open');
                }
            });

            // Close menu on escape key
            $(document).on('keydown', function(e) {
                if (e.keyCode === 27 && $body.hasClass('menu-open')) {
                    $menuToggle.trigger('click');
                    $menuToggle.focus();
                }
            });
        },

        /**
         * Desktop dropdown menus
         */
        dropdownMenus: function() {
            var $menuItems = $('.main-navigation .menu-item-has-children');
            var timeout;

            $menuItems.each(function() {
                var $item = $(this);
                var $link = $item.children('a');
                var $submenu = $item.children('.sub-menu');

                // Mouse enter
                $item.on('mouseenter', function() {
                    if ($(window).width() >= 768) {
                        clearTimeout(timeout);
                        $item.addClass('open');
                        $submenu.stop(true, true).fadeIn(200);
                        $link.attr('aria-expanded', 'true');
                    }
                });

                // Mouse leave
                $item.on('mouseleave', function() {
                    if ($(window).width() >= 768) {
                        timeout = setTimeout(function() {
                            $item.removeClass('open');
                            $submenu.stop(true, true).fadeOut(200);
                            $link.attr('aria-expanded', 'false');
                        }, 300);
                    }
                });

                // Click handling for touch devices
                $link.on('click', function(e) {
                    if ($(window).width() >= 768 && $submenu.length) {
                        if (!$item.hasClass('open')) {
                            e.preventDefault();
                            
                            // Close other open menus
                            $menuItems.not($item).removeClass('open').children('.sub-menu').fadeOut(200);
                            $menuItems.not($item).children('a').attr('aria-expanded', 'false');
                            
                            // Open this menu
                            $item.addClass('open');
                            $submenu.fadeIn(200);
                            $link.attr('aria-expanded', 'true');
                        }
                    }
                });
            });

            // Close dropdowns when clicking outside
            $(document).on('click', function(e) {
                if (!$(e.target).closest('.main-navigation').length) {
                    $menuItems.removeClass('open').children('.sub-menu').fadeOut(200);
                    $menuItems.children('a').attr('aria-expanded', 'false');
                }
            });
        },

        /**
         * Mega menu functionality
         */
        megaMenu: function() {
            var $megaMenuItems = $('.main-navigation .mega-menu');

            $megaMenuItems.each(function() {
                var $item = $(this);
                var $megaContent = $item.find('.mega-menu-content');

                // Position mega menu
                $item.on('mouseenter', function() {
                    if ($(window).width() >= 768) {
                        var windowWidth = $(window).width();
                        var itemOffset = $item.offset().left;
                        var megaWidth = $megaContent.outerWidth();
                        
                        // Center the mega menu under the item
                        var leftPosition = -(megaWidth / 2) + ($item.outerWidth() / 2);
                        
                        // Adjust if it goes off screen
                        if (itemOffset + leftPosition < 0) {
                            leftPosition = -itemOffset + 20;
                        } else if (itemOffset + leftPosition + megaWidth > windowWidth) {
                            leftPosition = windowWidth - itemOffset - megaWidth - 20;
                        }
                        
                        $megaContent.css('left', leftPosition + 'px');
                    }
                });
            });
        },

        /**
         * Sticky header
         */
        stickyHeader: function() {
            var $header = $('.site-header');
            var $window = $(window);
            var headerOffset = $header.offset().top;
            var isSticky = false;

            function updateStickyHeader() {
                var scrollTop = $window.scrollTop();
                
                if (scrollTop > headerOffset && !isSticky) {
                    $header.addClass('sticky');
                    $('body').addClass('sticky-header');
                    isSticky = true;
                } else if (scrollTop <= headerOffset && isSticky) {
                    $header.removeClass('sticky');
                    $('body').removeClass('sticky-header');
                    isSticky = false;
                }
            }

            // Only enable if sticky header is enabled in customizer
            if ($header.hasClass('sticky-enabled') || $('body').hasClass('sticky-header-enabled')) {
                $window.on('scroll', updateStickyHeader);
                updateStickyHeader(); // Initial check
            }
        },

        /**
         * Search functionality
         */
        searchFunctionality: function() {
            var $searchToggle = $('.search-toggle');
            var $searchContainer = $('.search-form-container');
            var $searchInput = $searchContainer.find('input[type="search"]');
            var $searchClose = $searchContainer.find('.search-close');

            // Toggle search form
            $searchToggle.on('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                
                $searchContainer.toggleClass('active');
                
                if ($searchContainer.hasClass('active')) {
                    setTimeout(function() {
                        $searchInput.focus();
                    }, 300);
                } else {
                    $searchToggle.focus();
                }
            });

            // Close search form
            $searchClose.on('click', function(e) {
                e.preventDefault();
                $searchContainer.removeClass('active');
                $searchToggle.focus();
            });

            // Close search when clicking outside
            $(document).on('click', function(e) {
                if (!$(e.target).closest('.header-search').length) {
                    $searchContainer.removeClass('active');
                }
            });

            // Close search on escape key
            $searchInput.on('keydown', function(e) {
                if (e.keyCode === 27) {
                    $searchContainer.removeClass('active');
                    $searchToggle.focus();
                }
            });

            // AJAX search functionality (if enabled)
            if ($searchContainer.hasClass('ajax-search')) {
                var searchTimeout;
                
                $searchInput.on('input', function() {
                    var query = $(this).val();
                    
                    clearTimeout(searchTimeout);
                    
                    if (query.length >= 3) {
                        searchTimeout = setTimeout(function() {
                            // Perform AJAX search
                            $.ajax({
                                url: aqualuxe_ajax.ajax_url,
                                type: 'POST',
                                data: {
                                    action: 'aqualuxe_ajax_search',
                                    query: query,
                                    nonce: aqualuxe_ajax.nonce
                                },
                                success: function(response) {
                                    if (response.success) {
                                        $('.search-results').html(response.data).show();
                                    }
                                }
                            });
                        }, 300);
                    } else {
                        $('.search-results').hide();
                    }
                });
            }
        },

        /**
         * Keyboard navigation
         */
        keyboardNavigation: function() {
            var $menuLinks = $('.main-navigation a');

            $menuLinks.on('keydown', function(e) {
                var $this = $(this);
                var $parent = $this.parent();
                var $submenu = $parent.find('.sub-menu').first();
                var $parentItem = $parent.parent().closest('.menu-item');

                switch (e.keyCode) {
                    case 9: // Tab
                        // Let default tab behavior happen
                        break;
                        
                    case 13: // Enter
                    case 32: // Space
                        if ($submenu.length && $(window).width() >= 768) {
                            e.preventDefault();
                            if ($parent.hasClass('open')) {
                                $parent.removeClass('open');
                                $submenu.fadeOut(200);
                                $this.attr('aria-expanded', 'false');
                            } else {
                                // Close other open menus
                                $('.main-navigation .menu-item').removeClass('open').find('.sub-menu').fadeOut(200);
                                $('.main-navigation .menu-item > a').attr('aria-expanded', 'false');
                                
                                // Open this menu
                                $parent.addClass('open');
                                $submenu.fadeIn(200);
                                $this.attr('aria-expanded', 'true');
                                $submenu.find('a').first().focus();
                            }
                        }
                        break;
                        
                    case 27: // Escape
                        if ($parent.parent().hasClass('sub-menu')) {
                            e.preventDefault();
                            $parentItem.removeClass('open').find('.sub-menu').fadeOut(200);
                            $parentItem.children('a').attr('aria-expanded', 'false').focus();
                        }
                        break;
                        
                    case 37: // Left arrow
                        if ($(window).width() >= 768) {
                            e.preventDefault();
                            var $prevItem = $parent.prev('.menu-item');
                            if ($prevItem.length) {
                                $prevItem.children('a').focus();
                            }
                        }
                        break;
                        
                    case 39: // Right arrow
                        if ($(window).width() >= 768) {
                            e.preventDefault();
                            var $nextItem = $parent.next('.menu-item');
                            if ($nextItem.length) {
                                $nextItem.children('a').focus();
                            }
                        }
                        break;
                        
                    case 38: // Up arrow
                        if ($parent.parent().hasClass('sub-menu') && $(window).width() >= 768) {
                            e.preventDefault();
                            var $prevSubmenuItem = $parent.prev('.menu-item');
                            if ($prevSubmenuItem.length) {
                                $prevSubmenuItem.children('a').focus();
                            } else {
                                // Go to parent menu item
                                $parentItem.children('a').focus();
                            }
                        }
                        break;
                        
                    case 40: // Down arrow
                        if ($submenu.length && $(window).width() >= 768) {
                            e.preventDefault();
                            if (!$parent.hasClass('open')) {
                                $parent.addClass('open');
                                $submenu.fadeIn(200);
                                $this.attr('aria-expanded', 'true');
                            }
                            $submenu.find('a').first().focus();
                        } else if ($parent.parent().hasClass('sub-menu') && $(window).width() >= 768) {
                            e.preventDefault();
                            var $nextSubmenuItem = $parent.next('.menu-item');
                            if ($nextSubmenuItem.length) {
                                $nextSubmenuItem.children('a').focus();
                            }
                        }
                        break;
                }
            });

            // Close menus when focus leaves navigation
            $menuLinks.last().on('blur', function() {
                setTimeout(function() {
                    if (!$('.main-navigation').find(':focus').length) {
                        $('.main-navigation .menu-item').removeClass('open').find('.sub-menu').fadeOut(200);
                        $('.main-navigation .menu-item > a').attr('aria-expanded', 'false');
                    }
                }, 150);
            });
        }
    };

    // Initialize when document is ready
    $(document).ready(function() {
        AquaLuxeNavigation.init();
    });

    // Handle window resize
    $(window).on('resize', function() {
        // Reset mobile menu state on resize
        if ($(window).width() >= 768) {
            $('.main-navigation').removeClass('toggled');
            $('.mobile-navigation').removeClass('active');
            $('.menu-toggle').attr('aria-expanded', 'false').removeClass('active');
            $('body').removeClass('menu-open');
            
            // Reset mobile submenu states
            $('.main-navigation .menu-item, .mobile-navigation .menu-item').removeClass('open');
            $('.main-navigation .sub-menu, .mobile-navigation .sub-menu').removeAttr('style');
        } else {
            // Close desktop dropdowns on mobile
            $('.main-navigation .menu-item').removeClass('open').find('.sub-menu').removeAttr('style');
        }
    });

})(jQuery);