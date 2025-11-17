/**
 * Navigation JavaScript
 * 
 * Handles responsive navigation and menu interactions
 */

(function($) {
    'use strict';

    const Navigation = {
        
        init: function() {
            this.initDropdowns();
            this.initMegaMenus();
            this.initKeyboardNavigation();
            this.initTouchNavigation();
        },

        initDropdowns: function() {
            $('.menu-item-has-children').each(function() {
                const $menuItem = $(this);
                const $link = $menuItem.children('a');
                const $submenu = $menuItem.children('.sub-menu');

                // Add aria attributes
                $link.attr('aria-haspopup', 'true').attr('aria-expanded', 'false');
                $submenu.attr('aria-hidden', 'true');

                // Desktop hover events
                $menuItem.on('mouseenter', function() {
                    $link.attr('aria-expanded', 'true');
                    $submenu.attr('aria-hidden', 'false').addClass('open');
                });

                $menuItem.on('mouseleave', function() {
                    $link.attr('aria-expanded', 'false');
                    $submenu.attr('aria-hidden', 'true').removeClass('open');
                });

                // Touch/click events for mobile
                $link.on('click', function(e) {
                    if (window.innerWidth <= 1024) {
                        e.preventDefault();
                        const isOpen = $submenu.hasClass('open');
                        
                        // Close other open submenus
                        $('.sub-menu.open').removeClass('open').attr('aria-hidden', 'true');
                        $('.menu-item-has-children > a').attr('aria-expanded', 'false');
                        
                        if (!isOpen) {
                            $submenu.addClass('open').attr('aria-hidden', 'false');
                            $link.attr('aria-expanded', 'true');
                        }
                    }
                });
            });
        },

        initMegaMenus: function() {
            $('.mega-menu').each(function() {
                const $megaMenu = $(this);
                const $trigger = $megaMenu.find('.mega-menu-trigger');
                const $content = $megaMenu.find('.mega-menu-content');

                $trigger.on('mouseenter focus', function() {
                    $content.addClass('open');
                });

                $megaMenu.on('mouseleave', function() {
                    $content.removeClass('open');
                });

                // Close on escape key
                $megaMenu.on('keydown', function(e) {
                    if (e.key === 'Escape') {
                        $content.removeClass('open');
                        $trigger.focus();
                    }
                });
            });
        },

        initKeyboardNavigation: function() {
            $('nav a, nav button').on('keydown', function(e) {
                const $current = $(this);
                const $parent = $current.closest('li');
                const $submenu = $parent.children('.sub-menu, .mega-menu-content');

                switch (e.key) {
                    case 'ArrowDown':
                        e.preventDefault();
                        if ($submenu.length) {
                            $submenu.addClass('open').attr('aria-hidden', 'false');
                            $current.attr('aria-expanded', 'true');
                            $submenu.find('a').first().focus();
                        } else {
                            const $next = $parent.next().find('a').first();
                            if ($next.length) $next.focus();
                        }
                        break;

                    case 'ArrowUp':
                        e.preventDefault();
                        const $prev = $parent.prev().find('a').first();
                        if ($prev.length) $prev.focus();
                        break;

                    case 'ArrowRight':
                        e.preventDefault();
                        if ($submenu.length) {
                            $submenu.addClass('open').attr('aria-hidden', 'false');
                            $current.attr('aria-expanded', 'true');
                            $submenu.find('a').first().focus();
                        }
                        break;

                    case 'ArrowLeft':
                        e.preventDefault();
                        const $parentMenu = $current.closest('.sub-menu');
                        if ($parentMenu.length) {
                            $parentMenu.removeClass('open').attr('aria-hidden', 'true');
                            $parentMenu.siblings('a').attr('aria-expanded', 'false').focus();
                        }
                        break;

                    case 'Escape':
                        e.preventDefault();
                        $('.sub-menu.open, .mega-menu-content.open').removeClass('open').attr('aria-hidden', 'true');
                        $('.menu-item-has-children > a, .mega-menu-trigger').attr('aria-expanded', 'false');
                        break;
                }
            });
        },

        initTouchNavigation: function() {
            let touchStartX = 0;
            let touchStartY = 0;

            $(document).on('touchstart', function(e) {
                touchStartX = e.originalEvent.touches[0].clientX;
                touchStartY = e.originalEvent.touches[0].clientY;
            });

            $(document).on('touchend', function(e) {
                const touchEndX = e.originalEvent.changedTouches[0].clientX;
                const touchEndY = e.originalEvent.changedTouches[0].clientY;
                const diffX = Math.abs(touchEndX - touchStartX);
                const diffY = Math.abs(touchEndY - touchStartY);

                // Swipe detection
                if (diffX > 100 && diffY < 100) {
                    if (touchEndX > touchStartX) {
                        // Swipe right - close mobile menu
                        $('.mobile-menu').removeClass('open');
                        $('.mobile-menu-overlay').removeClass('open');
                        $('body').removeClass('overflow-hidden');
                    } else {
                        // Swipe left - could trigger other actions
                    }
                }
            });
        }
    };

    // Initialize when ready
    $(document).ready(function() {
        Navigation.init();
    });

    // Make available globally
    window.AquaLuxeNavigation = Navigation;

})(jQuery);