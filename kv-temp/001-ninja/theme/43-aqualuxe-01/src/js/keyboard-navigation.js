/**
 * AquaLuxe Keyboard Navigation
 * 
 * Enhances keyboard navigation for dropdown menus and interactive elements
 * 
 * @package AquaLuxe
 * @since 1.0.0
 */

(function($) {
    'use strict';

    // Variables
    const KEYCODE = {
        TAB: 9,
        ENTER: 13,
        ESC: 27,
        SPACE: 32,
        LEFT: 37,
        UP: 38,
        RIGHT: 39,
        DOWN: 40
    };

    /**
     * Handle keyboard navigation for dropdown menus
     */
    function setupNavigation() {
        const navItems = $('.menu-item-has-children > a, .page_item_has_children > a');
        
        // Add aria attributes to menu items with children
        navItems.attr('aria-haspopup', 'true');
        navItems.attr('aria-expanded', 'false');
        
        // Add dropdown toggle buttons for keyboard users
        navItems.append('<span class="dropdown-toggle" aria-hidden="true"></span>');
        
        // Handle keyboard events
        navItems.on('keydown', function(e) {
            const $this = $(this);
            
            // ENTER or SPACE key
            if (e.which === KEYCODE.ENTER || e.which === KEYCODE.SPACE) {
                e.preventDefault();
                toggleSubmenu($this);
            }
        });
        
        // Handle dropdown toggle click
        $('.dropdown-toggle').on('click', function(e) {
            e.preventDefault();
            const $this = $(this).parent();
            toggleSubmenu($this);
        });
        
        // Close submenus when clicking outside
        $(document).on('click', function(e) {
            if (!$(e.target).closest('.menu-item-has-children, .page_item_has_children').length) {
                closeAllSubmenus();
            }
        });
        
        // Close submenus on ESC key
        $(document).on('keyup', function(e) {
            if (e.which === KEYCODE.ESC) {
                closeAllSubmenus();
                // Focus the first toggler that was expanded
                $('.menu-item-has-children > a[aria-expanded="true"], .page_item_has_children > a[aria-expanded="true"]').first().focus();
            }
        });
    }
    
    /**
     * Toggle submenu visibility
     * 
     * @param {jQuery} $menuItem The menu item element
     */
    function toggleSubmenu($menuItem) {
        const isExpanded = $menuItem.attr('aria-expanded') === 'true';
        const $submenu = $menuItem.next('.sub-menu, .children');
        
        // Close other open submenus at the same level
        $menuItem.parent().siblings().find('> a').attr('aria-expanded', 'false');
        $menuItem.parent().siblings().find('> .sub-menu, > .children').slideUp(200);
        
        // Toggle current submenu
        if (isExpanded) {
            $menuItem.attr('aria-expanded', 'false');
            $submenu.slideUp(200);
        } else {
            $menuItem.attr('aria-expanded', 'true');
            $submenu.slideDown(200);
            
            // Set focus to first item in submenu for keyboard users
            setTimeout(function() {
                $submenu.find('a').first().focus();
            }, 250);
        }
    }
    
    /**
     * Close all open submenus
     */
    function closeAllSubmenus() {
        $('.menu-item-has-children > a, .page_item_has_children > a').attr('aria-expanded', 'false');
        $('.sub-menu, .children').slideUp(200);
    }
    
    /**
     * Handle focus trap for modals and dialogs
     */
    function setupFocusTraps() {
        // Modal focus trap
        $('.modal, .dialog').each(function() {
            const $modal = $(this);
            const $focusableElements = $modal.find('a[href], button, input, textarea, select, [tabindex]:not([tabindex="-1"])');
            const $firstFocusable = $focusableElements.first();
            const $lastFocusable = $focusableElements.last();
            
            // Trap focus when modal is open
            $modal.on('keydown', function(e) {
                // If TAB key pressed
                if (e.which === KEYCODE.TAB) {
                    // If SHIFT + TAB
                    if (e.shiftKey) {
                        if (document.activeElement === $firstFocusable[0]) {
                            e.preventDefault();
                            $lastFocusable.focus();
                        }
                    } else {
                        // Just TAB
                        if (document.activeElement === $lastFocusable[0]) {
                            e.preventDefault();
                            $firstFocusable.focus();
                        }
                    }
                }
                
                // Close on ESC
                if (e.which === KEYCODE.ESC) {
                    $modal.find('.close-modal').trigger('click');
                }
            });
        });
    }
    
    /**
     * Enhance tab panel accessibility
     */
    function setupTabPanels() {
        $('.tabs').each(function() {
            const $tabs = $(this);
            const $tabList = $tabs.find('.tab-list');
            const $tabButtons = $tabs.find('.tab-button');
            const $tabPanels = $tabs.find('.tab-panel');
            
            // Add ARIA roles
            $tabList.attr('role', 'tablist');
            $tabButtons.attr('role', 'tab');
            $tabPanels.attr('role', 'tabpanel');
            
            // Set initial state
            $tabButtons.attr('aria-selected', 'false');
            $tabButtons.first().attr('aria-selected', 'true');
            
            // Connect tabs with panels
            $tabButtons.each(function(i) {
                const $button = $(this);
                const id = 'tab-' + i;
                const panelId = 'panel-' + i;
                
                $button.attr({
                    'id': id,
                    'aria-controls': panelId,
                    'tabindex': $button.is(':first-child') ? '0' : '-1'
                });
                
                $tabPanels.eq(i).attr({
                    'id': panelId,
                    'aria-labelledby': id,
                    'tabindex': '0'
                });
            });
            
            // Hide all panels except first
            $tabPanels.hide();
            $tabPanels.first().show();
            
            // Handle tab clicks
            $tabButtons.on('click', function() {
                activateTab($(this));
            });
            
            // Handle keyboard navigation
            $tabButtons.on('keydown', function(e) {
                const $current = $(this);
                let $target;
                
                switch(e.which) {
                    case KEYCODE.LEFT:
                    case KEYCODE.UP:
                        e.preventDefault();
                        $target = $current.prev();
                        if (!$target.length) {
                            $target = $tabButtons.last();
                        }
                        $target.focus();
                        activateTab($target);
                        break;
                        
                    case KEYCODE.RIGHT:
                    case KEYCODE.DOWN:
                        e.preventDefault();
                        $target = $current.next();
                        if (!$target.length) {
                            $target = $tabButtons.first();
                        }
                        $target.focus();
                        activateTab($target);
                        break;
                        
                    case KEYCODE.ENTER:
                    case KEYCODE.SPACE:
                        e.preventDefault();
                        activateTab($current);
                        break;
                }
            });
            
            /**
             * Activate a tab
             * 
             * @param {jQuery} $tab The tab element to activate
             */
            function activateTab($tab) {
                // Update tab states
                $tabButtons.attr({
                    'aria-selected': 'false',
                    'tabindex': '-1'
                });
                
                $tab.attr({
                    'aria-selected': 'true',
                    'tabindex': '0'
                });
                
                // Show associated panel
                const panelId = $tab.attr('aria-controls');
                $tabPanels.hide();
                $('#' + panelId).show();
            }
        });
    }
    
    // Initialize when DOM is ready
    $(document).ready(function() {
        setupNavigation();
        setupFocusTraps();
        setupTabPanels();
    });

})(jQuery);